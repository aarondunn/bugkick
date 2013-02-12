
<style>
        .hidden {
            display: none;
}
    </style>
<script type="text/javascript" charset="utf-8">
    function LoadTableList()
    {

        var statusId = 0;
        var labelId = 0;
        var accountId = 0;


        if (Request.QueryString("statusid").Count > 0) {
            statusId = Request.QueryString("statusid").Item(1);
            $("#status_ul").find("li a[href*="+ statusId +"]").css('color','red');
        }

        if (Request.QueryString("accountid").Count > 0) {
            accountId = Request.QueryString("accountid").Item(1);
            $("#people_ul").find("li a[href*="+ accountId +"]").css('color','red');
        }

        if (Request.QueryString("labelid").Count > 0) {
            labelId = Request.QueryString("labelid").Item(1);
            $("#label_ul").find("li a[href*="+ labelId +"]").css('color','red');
        }

        




    }
    $(document).ready(function() {
        LoadTableList();
        		
    });

                        
</script>
<?
$l_statusid = $l_labelid = $l_accountid = 0;
if ($_GET['statusid'])
    $l_statusid = $_GET['statusid'];

if ($_GET['accountid'])
    $l_accountid = $_GET['accountid'];

if ($_GET['labelid'])
    $l_labelid = $_GET['labelid'];





?>

<script>
        $(document).ready(function (){
           $("#q").keyup(function () {
    var filter = $(this).val(), count = 0;
    $(".filterbug:first li").each(function () {
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).addClass("hidden");
        } else {
            $(this).removeClass("hidden");
            count++;
        }
    });
    $("#filter-count").text(count);
});








$("#dialog-email").dialog({
			autoOpen: false,
			height: 200,
			width: 300,
			modal: true,
                        position: 'top',
			buttons: {
                            'Send Email': function() {
                                var content = ($(".filterbug").html().replace(/"/g,"'"));
                                if ($("#email").val() == "")
                                    {
                                    alert("Please enter email!");
                                    return;
                                }
                                
                                content = content.replace("?module=bug&action=new&id=","http://www.musopen.org/bugs/index.php?module=bug&action=new&id=");
                                $.ajax({
                                    type: "POST",
                                    url: "ajax_service.php",
                                    data: "content=" + escape(content)  + "&action=ExportEmail" + "&emailto=" + $("#email").val()
                                    ,
                                    success: function(html){
                                        
                                        $("#response").text(html);

                                        //$(this).dialog('close');



                                    }
                                });

				},
				Cancel: function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});


$("#ExportEmail").click(function (){

    $('#dialog-email').dialog('open');

    
  

});
        });
    </script>


    
<span style="float: right;"> </span>

<div id="dialog-email"  title="Send Email">


	<form action="" method="post">
	<fieldset>


		<label for="name">Email:</label>
		<input type="text" style="width:200px;"  name="email" id="email" class="text ui-widget-content ui-corner-all" />

                <br/>
		<div id="response">
		<!-- Our message will be echoed out here -->
	</div>
	</fieldset>
	</form>
</div>

<div class="filterbug" style="width:100%;">
<ul id="allbugs">
<?

$status_where_qr = "";

if ($l_statusid != 0)
    $status_where_qr = " and bug.status_id = " . $l_statusid;


$qr = "";

if ($l_accountid != 0 && $l_labelid == 0) // filter by account
    $qr = "select bug.id from bug,account_bug where bug.isarchive != 1 and bug.id = account_bug.bug_id and account_bug.account_id = " . $l_accountid . $status_where_qr;
else if ($l_accountid == 0 && $l_labelid != 0) // filter by label
    $qr = "select bug.id from bug,label_bug where bug.isarchive != 1 and bug.id = label_bug.bug_id and label_bug.label_id = " . $l_labelid . $status_where_qr;
else if ($l_accountid != 0 && $l_labelid != 0) // filter by label and account
    $qr = "select bug.id from bug,account_bug,label_bug where bug.isarchive != 1 and (bug.id = account_bug.bug_id and bug.id = label_bug.bug_id) and (account_bug.account_id = " . $l_accountid . " and label_bug.label_id = " . $l_labelid . $status_where_qr . ")";
else if ($l_accountid == 0 && $l_labelid == 0 && $l_statusid != 0)
    $qr = "select bug.id from bug where bug.isarchive != 1 and bug.status_id = " . $l_statusid;
else
    $qr = "select id from bug where bug.isarchive != 1";

global $link;



$result = mysql_query($qr, $link);



$bugs = array();

while (list($id) = mysql_fetch_row($result))
{



    $Bug = new Bug();

    $Bug->loadById($id);

    $bugs[] = $Bug;
?>
    <li style="list-style: none;">
    <?
    if ($Bug->duedate != "00/0/0000" && !is_null($Bug->duedate))
        $duedate_exist = 1;
    else
        $duedate_exist = 0; ?>

        <div   <? if ($duedate_exist == 1 && (strtotime($Bug->duedate) <= strtotime("now")))
            echo "class='bug_main_div'"." style='background-color:peachpuff;'"; else
            echo "class='bug_main_div'"; ?>>

<?php echo $Bug->id ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



            <a href="?module=bug&action=new&id=<?php echo $Bug->id ?>"><?php echo $Bug->title ?>
<? if ($Bug->duedate != "00/0/0000" && !is_null($Bug->duedate))
            echo "|| Duedate: " . $Bug->duedate; ?>
            </a>
<span style="display:none;"><?php echo "<br/>". $Bug->description; ?></span>



<?php foreach ($Bug->getLabels() as $label): ?>

            <div class="label_div"><?php echo $label->name; ?></div>

<?php endforeach; ?>



<?php foreach ($Bug->getAccounts() as $account): ?>

                <div class="people_div"><?php echo $account->name; ?></div>

<?php endforeach; ?>



                <div class="status_div"><?php echo $Bug->getStatus()->label; ?></div>



            </div>




        </li>
<?
}
?>
</ul>
</div>



