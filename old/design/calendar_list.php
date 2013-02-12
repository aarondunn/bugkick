

<label for="bug_status_id">Label:</label>
            <select name="label" id="SelectLabel">



                <?php
                $labels = GlobalData::GetAllLabels();
                ?><option value="0" >All</option>
                <?foreach ($labels as $label): ?>



<?php
                    $is_selected = "";


?>



                    <option value="<?php echo $label->id; ?>" <?php echo $is_selected; ?>><?php echo $label->name; ?></option>

                <?php endforeach; ?>

                </select>

&nbsp;&nbsp;&nbsp;
<label for="bug_status_id">Assigned to:</label>
            <select name="label" id="SelectAccount">



                <?php
                $accounts = GlobalData::GetAllAccounts();
                ?><option value="0" >All</option>
                <?foreach ($accounts as $account): ?>



<?php
                    $is_selected = "";


?>



                    <option value="<?php echo $account->id; ?>" <?php echo $is_selected; ?>><?php echo $account->name; ?></option>

                <?php endforeach; ?>

                </select>

<div  id="calendar">  </div>







<script type='text/javascript'>

	$(document).ready(function() {
                $('#SelectLabel,#SelectAccount').change(function() {
                  var labelid = $("#SelectLabel").val();
                  var accountid = $("#SelectAccount").val();
                  

                  $('#calendar').fullCalendar({
			editable: false,
			events: 'Testphp.php?labelid='+labelid+'&accountid='+accountid


		});
                });

                
		$('#calendar').fullCalendar({
			editable: false,
			events: 'Testphp.php'
                        
                        
		});

	});

</script>
<style>

    #calendar {
		width: 900px;
		margin: 0 auto;
                margin-left: 100px;
		}
</style>

