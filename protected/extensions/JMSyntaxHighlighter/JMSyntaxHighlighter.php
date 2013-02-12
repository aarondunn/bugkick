<?php

class JMSyntaxHighlighter extends CApplicationComponent {

    private $_aUrl = '';

    public $theme = 'Default';

    protected function registerScript() {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'syntaxhighlighter' . DIRECTORY_SEPARATOR;
        $this->_aUrl = Yii::app()->getAssetManager()->publish($assets);
        $cs->registerScriptFile($this->_aUrl . '/scripts/shCore.js');
        $cs->registerScriptFile($this->_aUrl . '/scripts/shAutoloader.js');
        $cs->registerCssFile($this->_aUrl . '/styles/shCore.css');
        $cs->registerCssFile($this->_aUrl . '/styles/shTheme' . $this->theme . '.css');
    }

    public function init() {
        $this->registerScript();
        parent::init();
    }

    public function addHighlighter() {

        Yii::app()->clientScript->registerScript(__CLASS__, '
                        function path()
                        {
                          var args = arguments,
                              result = []
                              ;

                          for(var i = 0; i < args.length; i++)
                              result.push(args[i].replace("@", "' . $this->_aUrl . '/scripts/"));

                          return result
                        };

                        SyntaxHighlighter.autoloader.apply(null, path(
                          "applescript            @shBrushAppleScript.js",
                          "actionscript3 as3      @shBrushAS3.js",
                          "bash shell             @shBrushBash.js",
                          "coldfusion cf          @shBrushColdFusion.js",
                          "cpp c                  @shBrushCpp.js",
                          "c# c-sharp csharp      @shBrushCSharp.js",
                          "css                    @shBrushCss.js",
                          "delphi pascal          @shBrushDelphi.js",
                          "diff patch pas         @shBrushDiff.js",
                          "erl erlang             @shBrushErlang.js",
                          "groovy                 @shBrushGroovy.js",
                          "java                   @shBrushJava.js",
                          "jfx javafx             @shBrushJavaFX.js",
                          "js jscript javascript  @shBrushJScript.js",
                          "perl pl                @shBrushPerl.js",
                          "php                    @shBrushPhp.js",
                          "text plain             @shBrushPlain.js",
                          "py python              @shBrushPython.js",
                          "ruby rails ror rb      @shBrushRuby.js",
                          "sass scss              @shBrushSass.js",
                          "scala                  @shBrushScala.js",
                          "sql                    @shBrushSql.js",
                          "vb vbnet               @shBrushVb.js",
                          "xml xhtml xslt html    @shBrushXml.js"
                        ));
                        SyntaxHighlighter.all();
                        SyntaxHighlighter.config.stripBrs = true;
			SyntaxHighlighter.all();
		', CClientScript::POS_END);
    }

}
