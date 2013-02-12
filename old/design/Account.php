<?php



class Account extends BaseModel

	{

    	public $id = "";

    	public $created_at;

    	public $name;

    	public $email;

    	public $comment;

        public $password;

        public $email_notify;

    	public $isadmin;

        public $profile_img;

    	public function loadById($id)

    	{

    		if(!is_numeric($id)) return;

    		

    		global $link;

			$result = mysql_query("SELECT id, created_at, name, email, comment,password, email_notify, isadmin,profile_img FROM account WHERE id=".$id, $link);

			list($this->id, $this->created_at, $this->name, $this->email, $this->comment, $this->password, $this->email_notify,$this->isadmin,$this->profile_img) = mysql_fetch_row($result);

    	}

    	

    	public function save()

    	{

    		global $link;

    		

    		if(!$this->id)

			{

				mysql_query("INSERT INTO account (created_at, name, email, comment,password, email_notify, isadmin, profile_img) VALUES(NOW(), '".$this->name."', '".$this->email."', '".$this->comment."', '".$this->password."', '".$this->email_notify."', '".$this->isadmin."', '".$this->profile_img."')", $link);

				$this->id = mysql_insert_id($link);

			}

			else

			{

				mysql_query("UPDATE account SET name='".$this->name."', email='".$this->email."', comment='".$this->comment."', password='".$this->password."', email_notify=".$this->email_notify.", isadmin =".$this->isadmin.", profile_img='".$this->profile_img. "' WHERE id=".$this->id, $link);

			}

    	}

	};



?>