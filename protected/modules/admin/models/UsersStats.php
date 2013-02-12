<?php
/**
 * UsersStats
 *
 * @author f0t0n
 */
class UsersStats extends CModel {
    
    /**
     * The total number of registered users;
     * @var int
     */
    public $usersNumber;
    /**
     * The number of users which have made at least 3 tickets
     * @var int
     */
    public $activeUsersNumber;
    
    /**
     *
     * @var UsersStats
     */
    protected static $instance;
    
    protected function __construct() {
        $this->initStatsProperties();
    }   
    
    protected function initStatsProperties() {
        $this->usersNumber = AUser::model()->countByAttributes(array(
            'userStatus'=>AUser::STATUS_ACTIVE
        ));
        $this->activeUsersNumber = AUser::model()->count(
                'userStatus=:active AND (SELECT COUNT(*) FROM {{bug}} WHERE owner_id = t.user_id) > :cnt',
                array(
                    ':active'=>AUser::STATUS_ACTIVE,
                    ':cnt'=>2
            )
        );
    }
    
    /**
     * @return UsersStats
     */
    public static function instance() {
        if(empty(self::$instance)) {
            self::$instance = new UsersStats();
        }
        return self::$instance;
    }
    
    //put your code here
    public function attributeNames() {
        return array(
            'usersNumber',
            'activeUsersNumber',
        );
    }
    
    public function attributeLabels() {
        return array(
            'usersNumber'=>'Total number of registered users',
            'activeUsersNumber'=>'Total number of active users',
        );
    }
}