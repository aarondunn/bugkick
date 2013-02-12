<?php

class GlobalData
{
	static public function GetAllLabels()
	{
    		global $link;
    		$result = mysql_query("SELECT DISTINCT label.id FROM label", $link);
    		
    		$labels = array();
    		while(list($label_id) = mysql_fetch_row($result))
    		{
    			$l =  new Label();
    			$l->loadById($label_id);
    			$labels[] = $l;
    		}
    			
    		return $labels;
	}
	
	static public function GetAllAccounts()
	{
    		global $link;
    		$result = mysql_query("SELECT DISTINCT account.id FROM account", $link);
    		
    		$accounts = array();
    		while(list($account_id) = mysql_fetch_row($result))
    		{
    			$l =  new Account();
    			$l->loadById($account_id);
    			$accounts[] = $l;
    		}
    			
    		return $accounts;
	}
	
	static public function GetAllStatuses()
	{
    		global $link;
    		$result = mysql_query("SELECT DISTINCT status.id FROM status", $link);
    		
    		$statuses = array();
    		while(list($status_id) = mysql_fetch_row($result))
    		{
    			$l =  new Status();
    			$l->loadById($status_id);
    			$statuses[] = $l;
    		}
    			
    		return $statuses;
	}
}

?>