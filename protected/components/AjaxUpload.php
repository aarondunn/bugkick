<?php
Yii::import('ext.EAjaxUpload.*');
/**
 * AjaxUpload
 *
 * @author f0t0n
 */
class AjaxUpload extends EAjaxUpload {
	
	const INIT_POS_LOAD = CClientScript::POS_LOAD;
	const INIT_POS_DIRECTLY = 100500;
	
	public $posInitUploader;
	public $buttonText = 'Upload Photo';
	public $buttonWidth = 80;
	public $buttonHeight = 30;
	
	public function __construct(
			$posInitUploader = self::INIT_POS_LOAD, 
			$owner = null) {
		parent::__construct($owner);
		$this->posInitUploader = $posInitUploader;
	}
	
	public function run() {
		if(empty($this->config['action']))
			throw new CException('EAjaxUpload: param "action" cannot be empty.');
		if(empty($this->config['allowedExtensions']))
			throw new CException('EAjaxUpload: param "allowedExtensions" cannot be empty.');
		if(empty($this->config['sizeLimit']))
			throw new CException('EAjaxUpload: param "sizeLimit" cannot be empty.');
		unset($this->config['element']);
		$assets = Yii::getPathOfAlias('ext.EAjaxUpload').'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		Yii::app()->clientScript->registerScriptFile(
			$baseUrl . '/fileuploader.js', CClientScript::POS_HEAD
		);
		$this->css=(!empty($this->css))?$this->css:$baseUrl.'/fileuploader.css';
		Yii::app()->clientScript->registerCssFile($this->css);
		$postParams = array(
			'PHPSESSID'=>session_id(),
			'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken
		);
		if(isset($this->postParams))
			$postParams = array_merge($postParams, $this->postParams);
		$config = array(
			'element'=>'js:document.getElementById("'.$this->id.'")',
			'debug'=>false,
			'multiple'=>false
		);
		$config = array_merge($config, $this->config);
		$config['params']=$postParams;
		$config['template'] =
<<<qqTemplate
	<div class="qq-uploader">
		<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>
		<div style="width: {$this->buttonWidth}px; height: {$this->buttonHeight}px"
				class="qq-upload-button">{$this->buttonText}</div>
		<ul class="qq-upload-list"></ul>
	</div>
qqTemplate;
		$config = CJavaScript::encode($config);
		echo
<<<HTML
	<div id="{$this->id}"><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>
HTML;
		$js =
<<<JS
		var FileUploader_{$this->id} = new qq.FileUploader({$config});
JS;
		if($this->posInitUploader == self::INIT_POS_DIRECTLY)
			echo '<script type="text/javascript">'.$js.'</script>';
		else
			Yii::app()->getClientScript()->registerScript(
				"FileUploader_{$this->id}", $js, CClientScript::POS_LOAD);
	}
}