<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 22.11.12
 * Time: 0:17
 */
Yii::app()->clientScript->registerScript('forumSearch', '
    $(".search_box").keyup(function(){
        var searchValue = $(".search_box").val();
        if(navigator.appName == "Microsoft Internet Explorer"){
            window.document.execCommand(\'Stop\');
        }
        else{
            window.stop();
        }
        $.ajax({
            async   : true,
            type    : "POST",
            url     : "' . CHtml::normalizeUrl(array('/forum/forum/')) . '",
            dataType: "html",
            data: $("#keywordSearch").serialize(),
            success: function(data){
                $(".topics-container").html(data);
            }
        });
        if(searchValue){
           $(\'.cancel-search\').show();
        }
        else{
            $(\'.cancel-search\').hide();
        }
    });
    $(\'.cancel-search\').on(\'click\', function(){
        $(this).prev(\'.search_box\').val(\'\').trigger(\'keyup\');
    })
', CClientScript::POS_END);
?>
<?php echo CHtml::beginForm(Yii::app()->createUrl('/forum/forum/'), 'POST', array('id'=>'keywordSearch','onsubmit'=>'return false')); ?>
<div class="search-container">
    <input type="text" name="forumSearchKeyword" class="search_box"
           value="<?php echo $forumSearchKeyword ?>" placeholder="<?php echo Yii::t('main', 'Search...')?>" />
    <a <?php if(empty($forumSearchKeyword)) echo 'style="display:none"'?> class="cancel-search" title="Cancel Search" href="#">x</a>
</div>
<?php echo CHtml::endForm(); ?>