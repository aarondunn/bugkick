<div class="flashes"> </div>
<div class="tickets-container">
    <div id="bug-list" class="list-view">
    <?php
    $this->widget('application.extensions.fullcalendar.FullcalendarGraphWidget',
        array(
            'data' => $bugs,
            'options'=>array(
                'editable'=>true,
                'eventDrop' => 'js: function(event,dayDelta,minuteDelta,revertFunc){
                     $.ajax({
                        async   : true,
                        type    : "POST",
                        url     : "' . $this->createUrl('bug/UpdateDuedate') . '",
                        dataType: "json",
                        data    : {
                            "id" : event.id,
                            "dayDelta" : dayDelta,
                            "YII_CSRF_TOKEN" : YII_CSRF_TOKEN
                        },
                        success: function(data){
                           $.flashMessage().message("The ticket duedate is updated.");
                        },
                        error: function(data){
                           //$.flashMessage().message("An error has occurred, please try again.");
                        },
                    });
                }'
            ),
            'htmlOptions'=>array(
                   'style'=>'margin: 0 auto;'
            ),
        )
    );
    ?>
    </div>
</div>