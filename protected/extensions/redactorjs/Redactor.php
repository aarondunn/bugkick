<?php
/**
 * Redactorjs widget
 *
 * @author Vincent Gabriel
 * v 1.0
 */
class Redactor extends CInputWidget {
	/**
	 * Editor language
	 * Supports: de, en, fr, lv, pl, pt_br, ru, ua
	 */
	public $lang = 'en';
	/**
	 * Editor toolbar
	 * Supports: default, mini
	 */
	public $toolbar = 'default';
	/**
	 * Html options that will be assigned to the text area
	 */
	public $htmlOptions = array();
	/**
	 * Editor options that will be passed to the editor
	 */
	public $editorOptions = array();
	/**
	 * Debug mode
	 * Used to publish full js file instead of min version
	 */
	public $debugMode = false;
	/**
	 * Editor width
	 */
	public $width = '100%';
	/**
	 * Editor height
	 */
	public $height = '400px';
	/**
	 * Display editor
	 */
    public function run() {
	
		// Resolve name and id
		list($name, $id) = $this->resolveNameID();

		// Get assets dir
        $baseDir = dirname(__FILE__);
        $assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'assets');

		// Publish required assets
		$cs = Yii::app()->getClientScript();
		
		$jsFile = $this->debugMode ? 'redactor.js' : 'redactor.min.js';
		$cs->registerScriptFile($assets.'/' . $jsFile);
		$cs->registerCssFile($assets.'/css/redactor.css');

        $this->htmlOptions['id'] = $id;

        if (!array_key_exists('style', $this->htmlOptions)) {
            $this->htmlOptions['style'] = "width:{$this->width};height:{$this->height};";
        }

		$options = CJSON::encode(array_merge($this->editorOptions, array('lang' => $this->lang, 'toolbar' => $this->toolbar)));

		        $js =<<<EOP
		$('#{$id}').redactor({$options});
EOP;
		// Register js code
		$cs->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);
	
		// Do we have a model
		if($this->hasModel()) {
            $html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        } else {
            $html = CHtml::textArea($name, $this->value, $this->htmlOptions);
        }

		echo $html;
    }
}
?>
