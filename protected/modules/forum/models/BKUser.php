<?php

/**
 * BKUser
 *
 * @author Evgeniy `f0t0n` Naydenov <t.34.oxygen@gmail.com>
 * @author Alexey Kavshirko <kavshirko@gmail.com>
 */
class BKUser extends ForumActiveRecord
{
    const FORUM_ROLE_ADMIN = 'admin';
    const FORUM_ROLE_MODERATOR = 'moderator';
    const FORUM_ROLE_USER = 'user';
    const FORUM_ROLE_BANNED = 'banned';

    protected static $_current_user = null;

    public static function model($className = __CLASS__) {
        /*if(empty($className)) {
            $className = Yii::app()->getModule('forum')->userModelClassName;
        }*/
        return parent::model($className);
    }

    public static function create($attributes=array()) {
        return self::model()->populateRecord($attributes);
    }

    public static function getUserModelClassName() {
        return Yii::app()->getModule('forum')->userModelClassName;
    }

    /**
     * Returns parent user model
     * @return User model
     */
    public static function clientModel()
    {
        return self::model(self::getUserModelClassName());
    }

    /**
   	 * @return string the associated database table name
   	 */
   	public function tableName()
   	{
   		return self::clientModel()->tableName();
   	}

    public function primaryKey()
    {
        return self::clientModel()->primaryKey();
    }

    /**
     * Returns current logged-in user's model or null if the user is guest.
     * @return User
     */
    public static function current()
    {
		if(empty(Yii::app()->user) || Yii::app()->user->isGuest) {
			return null;
		}
		if(empty(self::$_current_user)) {
			self::$_current_user = self::model()->findByPk(Yii::app()->user->id);
		}
		return self::$_current_user;
    }

    /**
     * Returns number of posts
     * @return string
     */
    public function getPostsCount()
    {
        return BKPost::model()->count('user_id = :id', array(':id'=>$this->id));
    }

    /**
   	 * @return array relational rules.
   	 */
   	public function relations()
   	{
        // NOTE: you may need to adjust the relation name and the related
   		// class name for the relations automatically generated below.
        return CMap::mergeArray(
            self::clientModel()->relations(),
            array(
                'posts' => array(self::HAS_MANY, 'BKPost', 'user_id'),
                'postsCount'=>array(self::STAT, 'BKPost', 'user_id'),
            )
        );
   	}

    /**
     * Returns user name
     * @return string representation of User model
     */
    public function repr()
    {
        return self::clientModel()->repr($this);
    }

    /**
     * Returns role property name in User model
     * @return string
     */
    public static function getUserRolePropertyName()
    {
        return Yii::app()->getModule('forum')->userRolePropertyName;
    }

    /**
     * Returns user role
     * @return int
     */
    public function getForumRole()
    {
        $roleName = self::getUserRolePropertyName();
        return $this->$roleName;
    }

    /**
     * Returns user photo
     * @return string representation of User model
     */
    public function getImage()
    {
        return self::clientModel()->getImage($this);
    }
}