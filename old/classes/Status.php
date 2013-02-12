<?php



class Status extends BaseModel

	{

    	public $id = "";

    	public $label = "";

        
    	

    	public function loadById($id)

    	{

    		if(!is_numeric($id)) return;

    		

    		global $link;

			$result = mysql_query("SELECT id, label FROM status WHERE id=".$id, $link);

			list($this->id, $this->label) = mysql_fetch_row($result);

    	}
    	
    	public function save()

    	{

    		global $link;

    		

    		if(!$this->id)

			{

				mysql_query("INSERT INTO status (label) VALUES('".$this->label."')", $link);

				$this->id = mysql_insert_id($link);

			}

			else

			{

				mysql_query("UPDATE status SET label='".$this->label."' WHERE id=".$this->id, $link);

			}

    	}
    	

	};



?>