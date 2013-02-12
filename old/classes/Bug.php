<?php
class Bug extends BaseModel

	{

    	public $id = "";

    	public $created_at;

    	public $title;

    	public $description;

    	public $status_id;
    	
    	public $duedate;

        public $isarchive;

    	

    	public function loadById($id)

    	{

    		if(!is_numeric($id)) return;

    		

    		global $link;

			$result = mysql_query("SELECT id, created_at, title, description, status_id,DATE_FORMAT(duedate,'%m/%e/%Y'),isarchive FROM bug WHERE id=".$id, $link);

			list($this->id, $this->created_at, $this->title, $this->description, $this->status_id, $this->duedate,$this->isarchive)   = mysql_fetch_row($result);

    	}



    	public function getCommentsArray()

    	{

    		global $link;

    		

    		$comments = array();

			$result = mysql_query("SELECT comment.created_at, comment.message, account.name,account.profile_img FROM comment, account WHERE comment.bug_id=".$this->id." AND comment.account_id = account.id", $link);

			

			$comment = array();

			while(list($comment['created_at'], $comment['message'], $comment['name'], $comment['profile_img']) = mysql_fetch_row($result))

				$comments[] = $comment;

			

			return $comments;

    	}

        public function Update($bugid,$value,$method)

    	{

    		global $link;
                if (trim($method) == "bug_description_edit")
                {mysql_query("UPDATE bug SET description='".$value."' WHERE id=".$bugid, $link);}
                else if (trim($method) == "bug_title_edit")
                {
                    mysql_query("UPDATE bug SET title='".$value."' WHERE id=".$bugid, $link);
                }

        }

    	public function save($account_bug_list = array(), $label_bug_list = array())

    	{

    		global $link;

    		$lDueDate = null;
                                if ($this->duedate != "")
                                    $lDueDate = date('Y-m-d', strtotime($this->duedate)) ;

                $status_close = mysql_query("SELECT label FROM status WHERE id=".$this->status_id, $link);

                if (!is_null($status_close))
                {
                $status_row = mysql_fetch_assoc($status_close);
                if ($status_row['label'] == "Closed")
                    $this->isarchive = 1;
                else
                    $this->isarchive = 0;
                }
                else
                   $this->isarchive = 0;


    		if(!$this->id)

			{
                                
                                

				mysql_query("INSERT INTO bug (created_at, title, description, status_id, duedate, isarchive) VALUES(NOW(), '".$this->title."', '".$this->description."', ".$this->status_id.", '".$lDueDate."', ".$this->isarchive.")", $link);

				$this->id = mysql_insert_id($link);

				

    foreach($this->getAccounts() as $account)

	    {

	$body=<<<END_SDS_ALERT

Dear {$account->name},



A bug were (re)assigned to you, please find detailed information below:



title: {$bug->title}

description: {$bug->description}



Regards

END_SDS_ALERT;



	$subject = "New bug were assigned to you: ".$bug->title;

	try {

	    @mail($account->email, $subject, $body);

	} catch (Exception $e) {

	    

	}

	

	    }

			}

			else

			{

				mysql_query("UPDATE bug SET title='".$this->title."', status_id='".$this->status_id."', duedate='".$lDueDate."', isarchive='".$this->isarchive."' WHERE id=".$this->id, $link);

			}

			

			$existing_labels_ids = array();

			foreach($this->getLabels() as $label)

				$existing_labels_ids[] = $label->id;

			foreach($existing_labels_ids as $id)

				if(!in_array($id, $label_bug_list)) 

					mysql_query("DELETE FROM label_bug WHERE label_id=".$id." AND bug_id=".$this->id, $link);    

			foreach($label_bug_list as $id)

				if(!in_array($id, $existing_labels_ids)) 

				    mysql_query("INSERT INTO label_bug (label_id, bug_id) VALUES (".$id.",".$this->id.")", $link);    

				    

			$existing_accounts_ids = array();

			foreach($this->getAccounts() as $account)

				$existing_accounts_ids[] = $account->id;

			foreach($existing_accounts_ids as $id)

				if(!in_array($id, $account_bug_list)) 

					mysql_query("DELETE FROM account_bug WHERE account_id=".$id." AND bug_id=".$this->id, $link);    

			foreach($account_bug_list as $id)

				if(!in_array($id, $existing_accounts_ids)) 

				    mysql_query("INSERT INTO account_bug (account_id, bug_id) VALUES (".$id.",".$this->id.")", $link);    

    	}

    	

    	public function getLabels()

    	{

    		global $link;

    		$result = mysql_query("SELECT DISTINCT label.id FROM label, label_bug, bug WHERE bug.id=".$this->id." AND label_bug.bug_id = bug.id AND label_bug.label_id = label.id", $link);

    		

    		$labels = array();

    		if($result)

    		while(list($label_id) = mysql_fetch_row($result))

    		{

    			$l =  new Label();

    			$l->loadById($label_id);

    			$labels[] = $l;

    		}

    			

    		return $labels;

    	}

    	

    	public function getAccounts()

    	{

    		global $link;

    		$result = mysql_query("SELECT DISTINCT account.id FROM account, account_bug, bug WHERE bug.id=".$this->id." AND account_bug.bug_id = bug.id AND account_bug.account_id = account.id", $link);

    		

    		$accounts = array();

    		if($result)

    		while(list($account_id) = mysql_fetch_row($result))

    		{

    			$l =  new Account();

    			$l->loadById($account_id);

    			$accounts[] = $l;

    		}

    			

    		return $accounts;

    	}

    	

    	public function getStatus()

	    {

    		global $link;

    		$result = mysql_query("SELECT DISTINCT status.id FROM status WHERE status.id=".$this->status_id, $link);

    		list($status_id) = mysql_fetch_row($result);

    		

			$l =  new Status();

			$l->loadById($status_id);    		

			

			return $l;

    	}

    	

    	public function getIsThisAccountAssigned($id)

    	{

    		if(!$this->id) return 0;

    		

    		global $link;

    		$result = mysql_query("SELECT DISTINCT * FROM account_bug WHERE account_bug.account_id=".$id." AND account_bug.bug_id=".$this->id, $link);

    		return mysql_num_rows($result);

    	}

    	

    	public function getIsThisLabelAssigned($id)

    	{

    		if(!$this->id) return 0;

    		

    		global $link;

    		$result = mysql_query("SELECT DISTINCT * FROM label_bug WHERE label_bug.label_id=".$id." AND label_bug.bug_id=".$this->id, $link);

    		return mysql_num_rows($result);

    	}

	};

      


?>