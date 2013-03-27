<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $comment_id
 * @property integer $created_at
 * @property string $message
 * @property integer $user_id
 * @property integer $bug_id
 *
 * @property Bug $bug
 * @property User $user
 */
class Comment extends GitHubRelated
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message, user_id, bug_id', 'required'),
			array('user_id, created_at, bug_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('comment_id, created_at, message, user_id, bug_id', 'safe', 'on'=>'search'),
		);
	}
    
    public function beforeValidate() {
		if(!Yii::app() instanceof CConsoleApplication) {
			$this->user_id = (int) Yii::app()->user->id;
		}
        return true;
    }

    public function beforeSave() {
        //$this->created_at = date("Y-m-d H:i:s"); //we use server timezone. Returned because time still showing incorrect
        $this->created_at = time();
        return true;
    }

    protected function afterSave() {
        if($this->bug->canTransmitToGitHub()) {
            $this->transmitToGitHub();
        }
        parent::afterSave();
    }

    protected function transmitToGitHub() {
        try {
            if($this->isNewRecord) {
                $this->createGitHubComment();
            }
        } catch(GithubException $e) {
            var_dump($e);
        } catch(CException $e) {
            var_dump($e);
        }
    }

    protected function createGitHubComment() {
        $this->getGitHubClient()->createComment(
            $this->bug->project->github_repo, $this->bug->githubIssue->number,
            $this->message);
    }

//    protected function afterFind()
//    {
//        $this->message = str_replace("\n", "<br>", $this->message);
//        return true;
//    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'user'=>array(self::BELONGS_TO,'User','user_id'),
            'bug'=>array(self::BELONGS_TO,'BugBase','bug_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'comment_id' => 'ID',
			'created_at' => 'Created At',
			'message' => 'Message',
			'user_id' => 'User',
			'bug_id' => 'Bug',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('comment_id',$this->id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('bug_id',$this->bug_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

    public static function getLastUserComments($userID, $numberComments = 10)
    {
        $rows = Yii::app()->db->cache(60)->createCommand()
        ->select('{{comment}}.created_at as created,
                  {{comment}}.message,
                  {{bug}}.number,
                  {{bug}}.title,
                  {{bug}}.project_id,
                  {{project}}.url_name')
        ->from('{{comment}}')
        ->where('{{comment}}.user_id=:userID', array(':userID'=>$userID))
        ->leftJoin('{{bug}}', '{{bug}}.id={{comment}}.bug_id')
        ->leftJoin('{{project}}', '{{project}}.project_id={{bug}}.project_id')
        ->order('{{comment}}.created_at DESC')
        ->limit($numberComments)
        ->queryAll();

        return $rows;
    }
    
}