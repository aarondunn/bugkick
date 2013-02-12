<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  <head>	<title>Bug tracking system</title>	   
 <link rel="shortcut icon" href="/favicon.ico" />    
<link rel="stylesheet" type="text/css" media="screen" href="design/css/reset-fonts-grids.css" />    
<link rel="stylesheet" type="text/css" media="screen" href="design/css/main.css" />    
<link rel="stylesheet" type="text/css" media="screen" href="design/css/base.css" />   	
<link type="text/css" href="design/css/redmond/jquery-ui-1.8rc3.custom.css" rel="stylesheet" />			
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>		
<script type="text/javascript" src="js/jquery-ui-1.8rc3.custom.min.js"></script>               
 <script type="text/javascript" src="js/jquery.jeditable.mini.js"></script>              
  <script type="text/javascript" src="js/CSJSRequestObject.js"></script>               
 <link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />               
 <script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>   

<style>a{color:#0073af;}
td{font-size:14px;text-align:left;}
table a:hover{text-decoration: underline;}
table{margin:0; padding:0;}
.filterbug a{color:#0073af;}
.filterbug li{margin:0;}</style>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript">
		$(document).ready(function() {
			/*
			*   Examples - various
			*/

			$("#various1").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});

		});
	</script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 	          
                   		                  </head>  <body>
<div id="doc3" class="yui-t7" style="min-width:995px">	




<div id="hd">		<div id="hd_left">		
<font size="3"><a href="<?php echo "?module=bug&action=list" ?>">bugs</a></font>		                       
 <input type="text" value="" name="q" id="q" />							</div>				
<div id="hd_right" style="text-align: right;">                <? if($current_member) { ?>    		
<a href="index.php" style="font-size:15px;">Home</a> |
 <!--<a href="?module=bug&action=new" style="font-size:15px;">-->
<a href="#" id="NewBug" style="font-size:15px;">New bug</a> |
 <a href="?module=calendar&action=list" style="font-size:15px;">Calendar</a> | 
<a href="?module=settings&action=list" style="font-size:15px;">Settings</a>                 <?}?>		</div>	</div>	<div id="bd">		<div id="yui-main">			
<div class="yui-b">				<div class="yui-ge">					

                       










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



$("#dialog-new-bug").dialog({
			autoOpen: false,
			height: 345,
			width: 770,
			modal: true,
			position: 'middle',
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});
$("#NewBug").click(function (){
    $('#dialog-new-bug').dialog('open');

});

$(".ui-widget-overlay").live("click", function() { $("#dialog-new-bug").dialog("close"); } );

$("#dialog-edit-bug").dialog({
			autoOpen: false,
			height: 200,
			width: 770,
			modal: true,
			position: 'middle',
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});
$("#EditBug").click(function (){
    $('#dialog-edit-bug').dialog('open');
});


$(".ui-widget-overlay").live("click", function() { $("#dialog-edit-bug").dialog("close"); } );


$("#dialog-edit-label").dialog({
			autoOpen: false,
			height: 120,
			width: 330,
			modal: true,
			position: 'middle',
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});
$(".EditLabel").click(function (){
    $('#dialog-edit-label').dialog('open');
});

$(".ui-widget-overlay").live("click", function() { $("#dialog-edit-label").dialog("close"); } );

$("#dialog-edit-status").dialog({
			autoOpen: false,
			height: 120,
			width: 330,
			modal: true,
			position: 'middle',
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});
$("#EditStatus").click(function (){
    $('#dialog-edit-status').dialog('open');
});

$(".ui-widget-overlay").live("click", function() { $("#dialog-edit-status").dialog("close"); } );


$("#dialog-edit-account").dialog({
			autoOpen: false,
			height: 330,
			width: 400,
			modal: true,
			position: 'top',
			close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
});
$("#EditAccount").click(function (){
    $('#dialog-edit-account').dialog('open');
});

        });

$(".ui-widget-overlay").live("click", function() { $("#dialog-edit-account").dialog("close"); } );
    </script>







          <div style="float:left; text-align:left;width:790px; padding-left:30px;"> 