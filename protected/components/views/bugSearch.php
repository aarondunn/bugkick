<?php
Yii::app()->clientScript->registerScript('searcher', '

    '.$redirectToTheMain.'

    $("#bugSearch").keyup(function(){
        var searchValue = $("#bugSearch").val();
        if(navigator.appName == "Microsoft Internet Explorer"){
            window.document.execCommand(\'Stop\');
        }
        else{
            window.stop();
        }
        $.ajax({
            async   : true,
            type    : "POST",
            url     : "' . CHtml::normalizeUrl(array('/bug/'. $act)) . '",
            dataType: "html",
            data: $("#keywordSearch").serialize(),
            success: function(data){
                $(".tickets-container").html(data);
                renderTicketUsersAndLabels();
                addTooltip();
                destroySortable();
                setupSortable();
                if(!!bugkick.bug.list) {
                    var listView$ = $("#bug-list");
                    $.each(bugkick.bug.list.CheckedItems, function(k, v) {
                        var item$ = $("#" + k, listView$);
                        if(!!item$.length) {
                            item$.find("div.checkbox").click();
                        } else {
                            delete bugkick.bug.list.CheckedItems[k];
                        }
                    });
                }
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
<?php echo CHtml::beginForm(Yii::app()->createUrl('/bug/'), 'POST', array('id'=>'keywordSearch','onsubmit'=>'return false')); ?>
<div class="search-container">
    <input type="text" name="filterText" class="search_box" id="bugSearch" value="<?php echo $filterText ?>" placeholder="<?php echo Yii::t('main', 'Search...')?>" />
    <a <?php if(empty($filterText)) echo 'style="display:none"'?> class="cancel-search" title="Cancel Search" href="#">Cancel Search</a>
</div>
<?php echo CHtml::endForm(); ?>