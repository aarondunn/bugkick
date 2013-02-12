<?php

class Label extends BaseModel
	{
    	public $id = "";
    	public $name;
    	
    	public function loadById($id)
    	{
    		if(!is_numeric($id)) return;
    		
    		global $link;
			$result = mysql_query("SELECT id, name FROM label WHERE id=".$id, $link);
			list($this->id, $this->name) = mysql_fetch_row($result);
    	}
    	
    	public function save()
    	{
    		global $link;
    		
    		if(!$this->id)
			{
				mysql_query("INSERT INTO label (name) VALUES('".$this->name."')", $link);
				$this->id = mysql_insert_id($link);
			}
			else
			{
				mysql_query("UPDATE label SET name='".$this->name."' WHERE id=".$this->id, $link);
			}
    	}
	};

?>