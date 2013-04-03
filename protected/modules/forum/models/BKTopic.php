<?php

/**
 * This is the model class for table "{{topic}}".
 *
 * The followings are the available columns in table '{{topic}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $forum_id
 * @property integer $topic_starter_id
 * @property string $time
 * @property int $archived
 *
 * The followings are the available model relations:
 * @property Post[] $posts
 * @property Forum $forum
 */
class BKTopic extends ForumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BKTopic the static model class
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
		return '{{topic}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time, title, forum_id, topic_starter_id', 'required'),
			array('forum_id, topic_starter_id, archived', 'numerical', 'integerOnly'=>true),
			array('title, description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, forum_id, time, archived', 'safe', 'on'=>'search'),
		);
	}

    public function scopes() {
        return array(
            'active' => array(
                'condition' => 't.archived=0',
            )
        );
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'posts' => array(self::HAS_MANY, 'BKPost', 'topic_id'),
			'postsCount' => array(self::STAT, 'BKPost', 'topic_id'),
			'forum' => array(self::BELONGS_TO, 'BKForum', 'forum_id'),
			'topicStarter' => array(self::BELONGS_TO, 'BKUser', 'topic_starter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'forum_id' => 'Forum',
			'topic_starter_id' => 'Topic Starter',
            'time' => 'Time',
            'archived' => 'Archived',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('forum_id',$this->forum_id);
		$criteria->compare('topic_starter_id',$this->topic_starter_id);
        $criteria->compare('time',$this->time,true);
        $criteria->compare('archived',$this->archived,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Returns all users who left posts in the topic
     * @return array
     */
    public function getTopicParticipants()
    {
        $users = array();
        $posts = $this->posts;
        if(!empty($this->topicStarter))
            $users[]=$this->topicStarter;

        if(!empty($posts) && is_array($posts)){
            foreach ($posts as $post){
                if(!empty($post->user))
                    $users[]=$post->user;
            }
        }
        return $users;
    }
}