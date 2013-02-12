window.filter_label = '';
window.filter_people = '';
window.filter_status = '';
window.search = '';

Event.observe(window, 'load', function() {
 if($('bug_title') && $('bug_title').value == '')
 {
 	Event.observe($('bug_title'), 'click', function() {
 			if($('bug_title').value == 'Henter Title Here')
 				$('bug_title').value = '';
 		});
 	$('bug_title').value = 'Henter Title Here';
 }
 
 $('open_accounts_menu_link').onclick = accountsMenuShow;
 $('open_labels_menu_link').onclick = labelsMenuShow;
});

Event.observe(window, 'click', function() {
	if(window.accounts_menu_open == 2)
	{
		$('accounts_menu_popup').style.visibility = 'hidden';
	}
	else
		window.accounts_menu_open++;
	
	if(window.labels_menu_open == 2)
	{
		$('labels_menu_popup').style.visibility = 'hidden';
	}
	else
		window.labels_menu_open++;
});

accountsMenuShow = function(event) 
{
	 window.accounts_menu_open = 1;
	 $('accounts_menu_popup').style.visibility = 'visible';
	 $('accounts_menu_popup').style.top = (Event.pointerY(event)-20)+'px';
	 $('accounts_menu_popup').style.left = (Event.pointerX(event)-50)+'px';	 
}



labelsMenuShow = function(event) 
{
	 window.labels_menu_open = 1;
	 $('labels_menu_popup').style.visibility = 'visible';
	 $('labels_menu_popup').style.top = (Event.pointerY(event)-20)+'px';
	 $('labels_menu_popup').style.left = (Event.pointerX(event)-50)+'px';	 
}

removeLabelToBugCreationForm = function(id)
{
	if($("create_bug_form") && $('hidden_label_'+id))
		$("create_bug_form").removeChild($('hidden_label_'+id));	
}

addLabelToBugCreationForm = function(id)
{
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "bug[label_bug_list][]");
	input.setAttribute("value", id);
	input.setAttribute("id", "hidden_label_"+id);
	$("create_bug_form").appendChild(input);
}

removeAccountToBugCreationForm = function(id)
{
	$("create_bug_form").removeChild($('hidden_account_'+id));	
}

addAccountToBugCreationForm = function(id)
{
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "bug[account_bug_list][]");
	input.setAttribute("value", id);
	input.setAttribute("id", "hidden_account_"+id);
	$("create_bug_form").appendChild(input);	
}



labelSelection = function(curr)
{
	var as = $$('#label_ul li a');
	for(var i = 0 ; i < as.length ; i++)
		as[i].style.color = '#1887c5';
		
	curr.style.color='#ff0000';
}

peopleSelection = function(curr)
{
	var as = $$('#people_ul li a');
	for(var i = 0 ; i < as.length ; i++)
		as[i].style.color = '#1887c5';
		
	curr.style.color='#ff0000';
}

statusSelection = function(curr)
{
	var as = $$('#status_ul li a');
	for(var i = 0 ; i < as.length ; i++)
		as[i].style.color = '#1887c5';
		
	curr.style.color='#ff0000';
}

scanSearchinput = function()
{
	if($('bug_search') && window.search != $('bug_search').value)
	{
		window.search = $('bug_search').value;
		refreshList();
	}
	
	setTimeout('scanSearchinput();', 100);
}

scanSearchinput();

refreshList = function()
{
	
	var to_hide = new Array();
	
	//----------------------------labels filter------------------------------------
	if(window.filter_label != '')
	{
		var bugs = $$('.bug_main_div');
		
		for(var i = 0 ; i < bugs.length ; i++)
		{
			hide_bug = true;
			var reg=/<div class="label_div">(.+)<\/div>/g;
			//alert(bugs[i].innerHTML);
			var arr=bugs[i].innerHTML.match(reg);
			if(arr != null)
			{
				for(var j = 0 ; j < arr.length ; j++)
				{
					var begin_len = '<div class="label_div">'.length;
					var label = arr[j].substr(begin_len, arr[j].length - begin_len - 6);
					if(label == window.filter_label)
						hide_bug = false;
				}
			}
			else
			{
				to_hide.push(bugs[i]);
			}
			if(hide_bug)
				to_hide.push(bugs[i]);
		}
	}
	//-----------------------------------------------------------------------------

	//----------------------------people filter------------------------------------
	if(window.filter_people != '')
	{
		var bugs = $$('.bug_main_div');
		
		for(var i = 0 ; i < bugs.length ; i++)
		{
			hide_bug = true;
			var reg=/<div class="people_div">(.+)<\/div>/g;
			//alert(bugs[i].innerHTML);
			var arr=bugs[i].innerHTML.match(reg);
			if(arr != null)
			{
				for(var j = 0 ; j < arr.length ; j++)
				{
					var begin_len = '<div class="people_div">'.length;
					var label = arr[j].substr(begin_len, arr[j].length - begin_len - 6);
					if(label == window.filter_people)
						hide_bug = false;
				}
			}
			else
			{
				to_hide.push(bugs[i]);
			}
			if(hide_bug)
				to_hide.push(bugs[i]);
		}
	}
	//------------------------------------------------------------------------------
	
	//----------------------------status filter------------------------------------
	if(window.filter_status != '')
	{
		var bugs = $$('.bug_main_div');
		
		for(var i = 0 ; i < bugs.length ; i++)
		{
			hide_bug = true;
			var reg=/<div class="status_div">(.+)<\/div>/g;
			//alert(bugs[i].innerHTML);
			var arr=bugs[i].innerHTML.match(reg);
			if(arr != null)
			{
				for(var j = 0 ; j < arr.length ; j++)
				{
					var begin_len = '<div class="status_div">'.length;
					var label = arr[j].substr(begin_len, arr[j].length - begin_len - 6);
					if(label == window.filter_status)
						hide_bug = false;
				}
			}
			else
			{
				to_hide.push(bugs[i]);
			}
			if(hide_bug)
				to_hide.push(bugs[i]);
		}
	}
	//------------------------------------------------------------------------------

	//----------------------------keys filter------------------------------------
	if(window.search != '')
	{
		var bugs = $$('.bug_main_div');
		var last_rating_index = 0;
		
		for(var i = 0 ; i < bugs.length ; i++)
		{
			hide_bug = true;
			var curr_rating_index = 0;
			
			keywords = search.split(' ');
			for(var p = 0 ; p < keywords.length ; p++)
			{
				if(keywords[p] == '' || keywords[p] == ' ') continue;
				var reg=new RegExp(keywords[p], 'g');
				var arr=bugs[i].innerHTML.match(reg);
				if(arr)
				{
					hide_bug = false;
					curr_rating_index++;
				}
			}
				
			if(curr_rating_index > last_rating_index)
			{
				last_rating_index = curr_rating_index;
				var removed = $('all_bus_id').removeChild(bugs[i]);
				$('all_bus_id').insertBefore(removed,$('all_bus_id').childNodes[0]);
			}
			
			if(hide_bug)
				to_hide.push(bugs[i]);
		}
	}
	//-----------------------------------------------------------------------------
	
	var bugs = $$('.bug_main_div');
	for(var i = 0 ; i < bugs.length ; i++)
		bugs[i].style.display = 'block';
	
	for(var j = 0 ; j < to_hide.length ; j++)
		to_hide[j].style.display = 'none';
}

proccessAccountContextMenuItem = function(id)
{
	$('account_id_'+id).style.display = 'inline'; 
	$('li_account_id_'+id).style.display = 'none'; 
	$('accounts_menu_popup').style.visibility = 'hidden'; 
	addAccountToBugCreationForm(id);
}

proccessLabelContextMenuItem = function(id)
{
	$('label_id_'+id).style.display = 'inline'; 
	$('li_label_id_'+id).style.display = 'none'; 
	$('labels_menu_popup').style.visibility = 'hidden'; 
	addLabelToBugCreationForm(id);
}

proccessLabelItemRemove = function(id)
{
	$('li_label_id_'+id).style.display = 'inline'; 
	$('label_id_'+id).style.display = 'none'; 
	removeLabelToBugCreationForm(id);
}

proccessAccountItemRemove = function(id)
{
	$('li_account_id_'+id).style.display = 'inline'; 
	$('account_id_'+id).style.display = 'none'; 
	removeAccountToBugCreationForm(id);
}























