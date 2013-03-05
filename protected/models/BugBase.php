<?php

/**
 * This is the model class for table "bug".
 *
 * The followings are the available columns in table 'bug':
 * @property integer $id
 * @property integer $github_issue_id FK(GithubIssue)
 * @property integer $number
 * @property integer $prev_number
 * @property integer $next_number
 * @property integer $prev_id
 * @property integer $next_id
 * @property integer $project_id
 * @property string $created_at
 * @property string $title
 * @property string $description
 * @property integer $status_id
 * @property integer $label_id
 * @property string $duedate
 * @property integer $isarchive
 * @property integer $company_id
 * @property integer $user_id
 * @property integer $notified
 * @property integer $duplicate_number
 * @property integer $priority_order
 * @property integer $is_created_with_api
 * @property string $api_user_email
 * @property string $type
 * @property string $user_set //contains serialized array of assigned users IDs (duplicates {{bug_by_user}})
 * @property string $label_set //contains serialized array of labels IDs (duplicates {{bug_by_label}})
 *
 * @property GithubIssue $githubIssue
 * @property User[] $user The users that are assigned to bug.
 * 
 */
class BugBase extends GitHubRelated {
    
    /**
     * Returns the static model of the specified AR class.
     * @return Bug the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bug}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, description', 'required'),
            array('github_issue_id, status_id, isarchive, label_id, company_id, user_id, owner_id, duplicate_number',
                'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('api_user_email', 'length', 'max' => 255),
            array('created_at, duedate', 'safe'),
            array('duplicate_number', 'application.extensions.validators.TicketNumberValidator'),
            array('priority_order', 'numerical', 'integerOnly'=>true),
			array('is_created_with_api, type, github_issue_id', 'default', 'value'=>null),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, github_issue_id, user_set, label_set, created_at, title, description, status_id, label_id, duedate, isarchive, company_id, user_id, owner_id, notified, duplicate_number, is_created_with_api, api_user_email, type',
                'safe', 'on' => 'search'),
        );
    }

    public function defaultScope() {
        return array(
            'order' => 'id DESC',
        );
    }

    protected function beforeSave() {
        if ($this->scenario == 'insert') {
            $this->created_at = new CDbExpression('NOW()');
			if(!empty(Yii::app()->user)) {
				if(!empty(Yii::app()->user->company_id)) {
					$this->company_id = Yii::app()->user->company_id;
				}
				if(!empty(Yii::app()->user->id) && $this->is_created_with_api==0) {
					$this->owner_id = Yii::app()->user->id;
				}
			}
            /*if($this->duedate == '')
                $this->duedate = new CDbExpression('DATE_ADD(NOW(), INTERVAL 1 YEAR)');
			*/
        }
        return parent::beforeSave();
    }

    protected function afterSave() {
        if($this->canTransmitToGitHub()) {
            $this->transmitToGitHub();
        }
        parent::afterSave();
    }

    protected function beforeDelete() {
        $shouldBeDeleted = parent::beforeDelete();
        if($shouldBeDeleted) {
            $this->updateGitHubIssue(GitHubClient::STATE_CLOSED);
        }
        return $shouldBeDeleted;
    }

    /**
     *
     * @return type
     *
     * @todo
     *      1. In case of exception should handle it.
     * For example, if user can't be authenticated anymore
     * since he has revoked the access of our application to his account.
     */
    protected function transmitToGitHub() {
        try {
            if($this->isNewRecord) {
                $this->createGitHubIssue();
            } else {
                $this->updateGitHubIssue();
            }
        } catch(GithubException $e) {
            var_dump($e);
        } catch(CException $e) {
            var_dump($e);
        }
    }

    public function canTransmitToGitHub() {
        $user = User::current();
        return !empty($user->githubUser)
            && !empty($user->githubUser->is_active)
            && $this->company->isGitHubIntegrationAvailable()
            && !empty($this->project->github_repo)
            && !empty($this->project->translate_tickets);
    }

    protected function titleTrimEnd() {
        return preg_replace('/\s*&#133;\s*$/', '', $this->title);
    }
    protected function createGitHubIssue() {
        $assignee = $this->getGitHubIssueAssignee();
        $attributes = $this->getGitHubClient()->createIssue(
            $this->project->github_repo, $this->titleTrimEnd(),
                $this->description, $assignee, null, array());
        $gitHubIssue = new GithubIssue();
        $gitHubIssue->setAttributes($attributes);
        if($gitHubIssue->save()) {
            return $this->updateByPk($this->id, array(
                'github_issue_id' => $gitHubIssue->id
            )) > 0;
        }
        return false;
    }

    protected function updateGitHubIssue($state = GitHubClient::STATE_OPEN) {
        if(!empty($this->githubIssue)) {
            if(!empty($this->isarchive)) {
                $state = GitHubClient::STATE_CLOSED;
            }
            $assignee = $this->getGitHubIssueAssignee();
            $attributes = $this->getGitHubClient()->editIssue(
                $this->project->github_repo, $this->githubIssue->number,
                $this->titleTrimEnd(), $this->description, $assignee, $state,
                null, array());
            return !empty($attributes);
        }
        return false;
    }

    /**
     * @return GithubUser
     */
    protected function getGitHubIssueAssignee() {
        $collaborators = $this->getGitHubClient()->getCollaborators(
            $this->project->github_repo);
        $possibleAssignees = array();
        if(is_array($collaborators)) {
            for($i = count($collaborators); --$i >= 0;) {
                if(isset($collaborators[$i]['login'])) {
                    $possibleAssignees[$collaborators[$i]['login']] = 1;
                }
            }
            $collaborators = null;
            unset($collaborators);
            $users = $this->user;
            foreach($users as $user) {
                if(!empty($user->githubUser)
                    && isset($possibleAssignees[$user->githubUser->login])) {
                    return $user->githubUser->login;
                }
            }
        }
        return null;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
		// NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'githubIssue'=>array(
                self::BELONGS_TO,
                'GithubIssue',
                'github_issue_id',
            ),
			//users assigned to bug
			'user'=>array(
				self::MANY_MANY,
				'User',
				'{{bug_by_user}}(bug_id, user_id)'
			),
			'label'=>array(
				self::MANY_MANY,
				'Label',
				'{{bug_by_label}}(bug_id, label_id)',
			),
			'project'=>array(self::BELONGS_TO, 'Project', 'project_id'),
            'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            //'label' => array(self::BELONGS_TO, 'Label', 'label_id'),
            //user assigned to bug
            //'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            //user creates bug
            'owner'=>array(self::BELONGS_TO, 'User', 'owner_id'),
            'comment' => array(self::HAS_MANY, 'Comment', 'bug_id'),
            'commentCount' => array(self::STAT, 'Comment', 'bug_id'),
            'notifications'=>array(
                self::HAS_MANY,
                'Notification',
                'user_id',
                'order' => 'date DESC',
            ),
            'attachments' => array(self::HAS_MANY, 'File', 'ticket_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created_at' => 'Created At',
            'title' => 'Title',
            'description' => 'Description',
            'status_id' => 'Status',
            'label_id' => 'Label',
            'duedate' => 'Duedate',
            'isarchive' => 'Closed',
            'company_id' => 'Company',
            'user_id' => 'Assignee',
            'notified' => 'User got a notification about expiration',
            'duplicate_number' => 'Duplicate of #',
			'is_created_with_api'=>'Is created with API',
			'api_user_email'=>'Email of user who submitted a bug with API',
			'type'=>'Type',
			'user_set'=>'Users Set',
			'label_set'=>'Labels Set',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        //$criteria->compare('id',$this->id);
        //$criteria->compare('created_at',$this->created_at,true);
        $criteria->compare('title', $this->title, true, 'OR');
        $criteria->compare('description', $this->description, true, 'OR');
        $criteria->compare('company_id', Company::current());
        $criteria->compare('isarchive',$this->isarchive);
        /*
          $criteria->compare('status_id',$this->status_id);
          $criteria->compare('label_id',$this->status_id);
          $criteria->compare('duedate',$this->duedate,true);

          $criteria->compare('company_id',$this->company_id);
          $criteria->compare('user_id',$this->user_id);
         *
         */

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function comment() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public function scopes() {
        return array(
            'currentCompany' => array(
                'condition' => 't.company_id=' . Company::current(),
            )
        );
    }
    
    

    //returned word alias from difference bug duedate and current date
    public static function getDueDateRemainAlias($bug)
    {
		if(!empty($bug) && strtotime($bug->duedate)>0) {
			$delta = strtotime($bug->duedate) - time();
			$dayDelta = round($delta / 24 / 60 / 60); //24/60/60 days in second
			if($dayDelta==0) return 'zeroDay';
			switch((int) $dayDelta) {
				case ($dayDelta>2) : return 'moreTwoDays';
				case ($dayDelta==2) : return 'twoDays';
				case ($dayDelta==1) : return 'oneDay';
				case ($dayDelta<0) : return  'negativeDays';
			}
		} else {
			return 'moreTwoDays';
		}
    }

    public static function getLastUserBugs($userID, $numberBugs = 10)
    {
        $criteria = new CDbCriteria;
        $criteria->limit = $numberBugs;
        $criteria->with = array(
             'user'=>array('condition'=>'user.user_id='.$userID, 'OR'),
             'owner'=>array('condition'=>'user.user_id='.$userID, 'OR'),
             'project'
        );
        $criteria->order = 'id DESC';
        $criteria->together=true;
        $criteria->distinct=true;
      //  $criteria->addCondition('owner_id='.$userID, 'AND');
        $rows = Bug::model()->resetScope()
                            ->currentCompany()
							->cache(60)
                            ->findAll($criteria);
        return $rows;
    }

    public static function getOutdatedTickets()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = '(DATE(duedate) < DATE(NOW()))
                  AND ( duedate !=\'0000-00-00\')
                  AND ( isarchive IS NULL)
                  AND ( notified = 0)';

        $tickets = self::model()->findAll($criteria);
        if(is_array($tickets)){
		   $ticketsArray=array();
            foreach($tickets as $value){
                $users = User::model()->bugRelated($value)->findAll();
                foreach ($users as $usr){
                    $ticketsArray[$usr->user_id][] = $value;
                }
            }
            return $ticketsArray;
        }
        return false;
    }

    public static function markTicketsAsNotified(array $tickets)
    {
        foreach($tickets as $value){
            Yii::app()->db->createCommand()->update('{{bug}}',
            array('notified'=>1),
            'id=:id', array(':id'=>$value->id));
        }
    }

    public static function getAPITasksCount()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'is_created_with_api=1 AND isarchive IS NULL';
        $project = Project::getCurrent();
        if(!empty($project) && $project->project_id > 0){
            $criteria->condition .= ' AND project_id=' . $project->project_id;
        }
        return $count = self::model()->count($criteria);
    }

    public static function getTicketsUserAndLabelSets(array $tickets)
    {
        if(!empty($tickets) && is_array($tickets)){
            $userIDs = $labelIDs = array();
            foreach($tickets as $ticket){
                if(!empty($ticket->user_set)){
                    $userIDs = array_merge($userIDs, CJSON::decode($ticket->user_set));
                }
                if(!empty($ticket->label_set)){
                    $labelIDs = array_merge($labelIDs, CJSON::decode($ticket->label_set));
                }
            }
            $userIDs = array_unique($userIDs);
            $labelIDs = array_unique($labelIDs);

            $users = $labels = null;

            if(!empty($userIDs)){
                $users = User::model()->ticketsList()->findAll(
                    'user_id IN ('.implode(',',$userIDs).')'
                );
            }

            if(!empty($labelIDs)){
                $labels = Label::model()->ticketsList()->findAll(
                    'label_id IN ('.implode(',',$labelIDs).')'
                );
            }

            $userData = $labelData = array();
            if(!empty($users) && is_array($users)){
                foreach($users as $usr){
                   $userData[$usr->user_id]['name'] = CHtml::encode(trim($usr->name . ' '. $usr->lname));
                   $userData[$usr->user_id]['profile_img'] = $usr->getImageSrc(31,31);
                }
            }
            if(!empty($labels) && is_array($labels)){
                foreach($labels as $lbl){
                   $labelData[$lbl->label_id]['name'] = CHtml::encode($lbl->name);
                   $labelData[$lbl->label_id]['label_color'] = CHtml::encode($lbl->label_color);
                }
            }
            return array('users'=>$userData, 'labels'=>$labelData);
        }
        return null;
    }
}