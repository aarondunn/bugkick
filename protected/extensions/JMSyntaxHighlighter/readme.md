#SyntaxHighlighter component for Yii framework.

This application component wraps SyntaxHighlighter by Alex Gorbatchev


##Install
Unpack extension into a directory of your choice, the extensions directory would be a good choice.

Add to config/main.php:
~~~
[php]
    'components' => array(
			'syntaxhighlighter' => array(
				'class' => 'ext.JMSyntaxHighlighter.JMSyntaxHighlighter',
			),
            ...
    ),
~~~

If you want a theme other than the default:	
~~~
[php]
    'components' => array(
			'syntaxhighlighter' => array(
				'class' => 'ext.JMSyntaxHighlighter.JMSyntaxHighlighter',
				'theme' => 'Django',
			),
            ...
    ),
~~~

You can choose from the following themes:
	Default (the default if none provided)
	Django
	Eclipse
	Emacs
	FadeToGrey
	MDUltra
	Midnight
	RDark
	
##Usage
Insert this code where you want the syntaxhighlighter script to be:
~~~
[php]
<?php Yii::app()->syntaxhighlighter->addHighlighter(); ?>
~~~
This could be in your main layout, but it's up to you.

The script will highlight any pre tag having the "brush" class:
~~~
[html]
<pre class="brush : php">
function Hello($world) {
    echo 'Hello ' . $world;
}
</pre>
~~~
	
Available brushes are:


~~~
[php]
	_Brush_					:	_Language
	applescript				:	AppleScript
	actionscript3 as3		:	AS3
	bash shell				:	Bash
	coldfusion cf			:	ColdFusion
	cpp c					:	Cpp
	c# c-sharp csharp		:	CSharp
	css						:	Css
	delphi pascal			:	Delphi
	diff patch pas			:	Diff
	erl erlang				:	Erlang
	groovy					:	Groovy
	java					:	Java
	jfx javafx				:	JavaFX
	js jscript javascript	:	JScript
	perl pl					:	Perl
	php						:	Php
	text plain				:	Plain
	py python				:	Python
	ruby rails ror rb		:	Ruby
	sass scss				:	Sass
	scala					:	Scala
	sql						:	Sql
	vb vbnet				:	Vb
	xml xhtml xslt html		:	Xml

~~~

For example, to highlight a bash script, you can use either _bash_ or _shell_:
~~~
[html]
<pre class="brush : shell">...</pre>
~~~

This component makes use of the SyntaxHighlighter autoload feature, so that only the brushes used are actually loaded into the page.
	
##Tricks
TinyMCE is great, but it does not want to leave the <pre> tags alone!
Don't worry, there's a solution to this problem:
Grab and install the [Wysiwyg Pre Element Fix](http://drupal.org/project/wysiwyg_preelementfix "Wysiwyg Pre Element Fix") and enjoy! :)

##Resources
 * [Forum Topic](http://www.yiiframework/)
 * [SyntaxHighlighter](http://alexgorbatchev.com/SyntaxHighlighter/ "http://alexgorbatchev.com/SyntaxHighlighter/")
