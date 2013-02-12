<?php
/**
 * EJWisiwyg
 * 
 * @see https://github.com/akzhan/jwysiwyg
 * @author f0t0n
 */
class EJWisiwyg extends CInputWidget {
	
	/**
	 * An array of options to be encoded to JavaScript object
	 * @see https://github.com/akzhan/jwysiwyg
	 */
	public $options=array();
	protected $scriptUrl;
	protected $scriptFile;
	
	public function init() {
		$this->scriptUrl=Yii::app()->assetManager->publish(
			dirname(__FILE__).'/assets',
			false,
			-1,
			YII_DEBUG
		);
		$this->scriptFile=YII_DEBUG?'jquery.wysiwyg.js':'jquery.wysiwyg.min.js';
		$this->registerClientScript();
		parent::init();
	}
	
	public function run() {
		echo $this->hasModel()
			? CHtml::activeTextArea(
				$this->model,$this->attribute,$this->htmlOptions)
			: CHtml::textArea($this->name,$this->value,$this->htmlOptions);
		parent::run();
	}
	
	/**
	 * Register CSS and JavaScript.
	 */
	protected function registerClientScript(){
		$id=$this->id;
		$cs=Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->scriptUrl.'/'.$this->scriptFile);
		$options=CJavaScript::encode($this->options);
		$cs->registerScript(
			__CLASS__.'#'.$id,
			"jQuery('#$id').wysiwyg($options);",
			CClientScript::POS_READY
		);
		$cs->registerCssFile($this->scriptUrl.'/jquery.wysiwyg.css');
	}
}