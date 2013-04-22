<div class="form" id="commentBlock">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
    'action'=>Yii::app()->createUrl('comment/create', array('bugId'=>$bug->id) ),
    'enableAjaxValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
));

    //resizable comment area (not wysiwyg)
    Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/plug-in/autoresize/jquery.autoresize.min.js' );
    Yii::app()->clientScript->registerScript('autoresize', '
        $("#Comment_message").autoResize({ });
         $("#Comment_message").val("");
    ', CClientScript::POS_READY);

?>

	<div class="row txtAreaBlock">
		<?php /*
		<img alt="Insert the Code" title="<?php echo Yii::t('main','Insert The Code'); ?>" class="imgBtn codeBtn"
			 src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/code.png" /> 
		 */
		?>
<!--		<script type="text/javascript">
        var editorDoc;
        function InitEditable () {
            var editor = document.getElementById ("editor");
            editorDoc = editor.contentWindow.document;
            var editorBody = editorDoc.body;

                // turn off spellcheck
            if ('spellcheck' in editorBody) {    // Firefox
                editorBody.spellcheck = false;
            }

            if ('contentEditable' in editorBody) {
                    // allow contentEditable
                editorBody.contentEditable = true;
            }
            else {  // Firefox earlier than version 3
                if ('designMode' in editorDoc) {
                        // turn on designMode
                    editorDoc.designMode = "on";
                }
            }
        }

        function ToggleBold () {
            editorDoc.execCommand ('bold', false, null);
        }
        InitEditable();
    </script>-->

		<?php

		echo $form->hiddenField($model,'bugId',array('value'=>$bug->id));
        if ( $useWysiwyg == 0 ){
            echo $form->textArea($model,'message',array(
                'rows'=>6,
                'cols'=>50,
                'style'=>'width:677px;max-width:677px;',
            ));
        }
        else{
            $visible=array('visible'=>true);
            $this->widget(
                'ext.jwysiwyg.EJWisiwyg',
                array(
                    'id'=>'Comment_message',
                    'model'=>$model,				// Data-Model
                    'attribute'=>'message',			// Attribute in the Data-Model
                    'options'=>array(
                        'initialContent'=>'',
                        /**
                         * @see jQueryUI resizable
                         */
                        'resizeOptions'=>array(
                            'maxWidth'=>555,
                            'minWidth'=>555,
                            'width'=>555,
                            'minHeight'=>80,
                            'height'=>80,
                        ),
                        'rmUnusedControls'=>true,
                        'controls'=>array(
                            'h1'=>$visible,
                            'h2'=>$visible,
                            'h3'=>$visible,
							'br'=>!$visible,
							'pre'=>!$visible,
                            'bold'=>$visible,
                            'italic'=>$visible,
                            'underline'=>$visible,
                            'strikeThrough'=>$visible,
                            //'justifyLeft'=>$visible,
                            //'justifyCenter'=>$visible,
                            //'justifyRight'=>$visible,
                            //'justifyFull'=>$visible,
                            //'undo'=>$visible,
                            //'redo'=>$visible,
                            'insertOrderedList'=>$visible,
                            'insertUnorderedList'=>$visible,
                            'insertHorizontalRule'=>$visible,
                            'increaseFontSize'=>$visible,
                            'decreaseFontSize'=>$visible,
                            'codeBtn'=>array(
                                'groupIndex'=>100500,
                                'custom'=>true,
                                'visible'=>true,
                                'icon'=>Yii::app()->theme->baseUrl.'/images/icons/code.png',
                                'tooltip'=>Yii::t('main','Insert the code'),
                                'exec'=>'js:function(){insertCodeDlg.dialog("open");}',
                            ),
                        ),
                        'events'=>array(
                            'keyup'=>($useWysiwyg == 0)
                                ? 'js:bugkick.bug.view.onCommentAreaKeyUp'
                                : 'js:function(){
                                    bugkick.bug.view.onCommentAreaKeyUp();
                                    bugkick.bug.view.checkEmpty();
                                }'
                        ),
                    ),
                    'htmlOptions'=>array(
                        'style'=>'max-width:555px;min-width:555px;width:555px;',
                        'cols'=>50,
                        'rows'=>6,
                    ),
                )
            );
        }

		?>
		<?php /*
		$this->widget(
			'ext.markitup.EMarkitupWidget',
			array(
				'model'=>$model,
				'attribute'=>'message',
				'settings'=>'markdown',
				'theme'=>'simple',
				'htmlOptions'=>array('rows'=>6, 'cols'=>50),
				'options'=>array(
					'previewAutoRefresh'=>false,
					'previewParserPath'=>
						Yii::app()->urlManager->createUrl('site/previewMarkdown'),
				),
			)
			
			
			
			
		);*/
		?>
		<?php echo $form->error($model,'message'); ?>
	</div>
	<!--<textarea rows="5" cols="20"></textarea>-->
    <div class="row buttons">
        <?php
            echo CHtml::link(Yii::t('main', 'Show Advanced <span class="arrow-down"></span>'), '#', array(
                'id'=>'showAdvancedOptions',
                'class'=>'button light-gray',
                'tabindex'=>'-1',
            ));
           /* echo CHtml::link(Yii::t('main', 'Post Comment'), '#', array(
                'id'=>'postCommentBtn',
                'class'=>'bkButtonBlueSmall normal',
            ));*/
            echo CHtml::ajaxButton(Yii::t('main', 'Post Comment'),
                $this->createUrl('bug/UpdateAjaxComment'),
                array('type' => 'POST',
                    'beforeSend'=>'js:function(){
                        var commentArea$ = $("#Comment_message").val();
                        if(!commentArea$ || /^\s*$/.test(commentArea$)){
                            $("#postCommentBtn").removeAttr("disabled");
                            return false;
                        }
                    }
                    ',
                    'success' => 'function(html){

                        if($(html).find(".ticket_content").length==""){
                            window.location.href="' . $this->createUrl('/bug') . '";
                            return false;
                        }

                        $(".ticket_content").html($(html).find(".ticket_content").html());
                        $("ul.message").html($(html).find("ul.message").html());
                        $("#sidebar").html($(html).find("#sidebar").html());
                        $("#bug-update-form").html($(html).find("#sidebar").html());
                        $("#Comment_message").val("");
                        $("#postCommentBtn").removeAttr("disabled");
                        if($("#Comment_message-wysiwyg-iframe").length>0){
                            $(document.getElementById("Comment_message-wysiwyg-iframe").contentWindow.document.body).html("");
                        }
                        if($("#showAdvancedOptions").hasClass("open")){
                            $("#showAdvancedOptions").click();
                        }
                        var adjustheight = 30;
                        $(".commentBlock").each(function(index) {
                            if($(this).height()>adjustheight){
                                $(this).find(".commentMessageFull").css("height", adjustheight).css("overflow", "hidden");
                                $(this).append(\'<div class="expand-icon"></div>\');
                            }
                        });
                        //convert comments time to local
                        bugkick.time.toLocal(".utc-timestamp-date", "Do MMMM YYYY - h:mm a");

                        $(".photo a[title]").colorTip({color:"yellow", timeout:100});
                        $("li.comment[title]").colorTip({color:"yellow", timeout:100});
                        $("li.print[title]").colorTip({color:"yellow", timeout:100});
                        $("li.delete[title]").colorTip({color:"yellow", timeout:100});
                        $("li.duplicate[title]").colorTip({color:"yellow", timeout:100});
                        $("span.tip-deleted[title]").colorTip({color:"yellow", timeout:100});
                        return false;
                      }'
                ),
                array(
                    'id'=>'postCommentBtn',
                    'onclick'=>'
                        $(this).attr("disabled","disabled");
                    ',
                    'class'=>'bkButtonBlueSmall normal',
                    'style'=>'width: 145px;margin-top: 0px;height:32px')
            );
            if (!$bug->isarchive){
                echo CHtml::link(Yii::t('main', 'Comment and Close'), '#', array(
                    'id'=>'postCommentCloseBtn',
                    'class'=>'bkButtonGraySmall normal',
                ));
            }
        ?>
        <?php $this->renderPartial('_comment_advanced_options'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<?php
 /*
  * Usernames auto-complete
  */
 Yii::app()->clientScript->registerCssFile( Yii::app()->baseUrl.'/js/plug-in/at-username/jquery.at-username.css' );?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/plug-in/at-username/jquery.at-username.js' );?>
<?php Yii::app()->clientScript->registerScript('atUsername', '
        $("#Comment_message").atUsername({
            xhrUsernames: "'.$this->createUrl('project/getUsersList').'"
        });
    ', CClientScript::POS_READY);?>
