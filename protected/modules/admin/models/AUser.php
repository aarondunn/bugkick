<?php
/**
 * AUser
 *
 * @author f0t0n
 */
class AUser extends User {
    
    /**
     * Returns the static model of the specified AR class.
     * @return AUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function relations() {
        $relations = parent::relations();
        if(!Yii::app() instanceof CConsoleApplication) {
			$relations['bugCount']=array(
				self::STAT,
				'ABug',
				'{{bug_by_user}}(user_id, bug_id)'
			);
            $relations['bugCreatedCount'] = array(
                self::STAT,
                'ABug',
                'owner_id',
            );
		}
        return $relations;
    }
    
    /**
     *
     * @return \CActiveDataProvider 
     */
    public function gridSearch(CDbCriteria $additionalCriteria = null) {
        $criteria = new CDbCriteria();
        
        if(!empty($additionalCriteria)) {
            $criteria->mergeWith($additionalCriteria);
        }
        
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('facebook_id', $this->facebook_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('lname', $this->lname, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('isadmin', $this->isadmin);
        $criteria->compare('is_global_admin', $this->is_global_admin);
        $criteria->compare('userStatus', $this->userStatus);
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize' => 60
            ),
        ));
    }
    
    public function gridSearchRecent($daysCount = 30) {
        $criteria = new CDbCriteria();
        $criteria->condition = 'DATEDIFF(NOW(), t.created_at) < :countDays';
        $criteria->addCondition('userStatus = :status_active');
        $criteria->params = array(
            ':status_active'=>self::STATUS_ACTIVE,
            ':countDays'=>$daysCount,
        );
        $criteria->with = array(
            'bugCount',
            'bugCreatedCount',
            'invitedUsersCount',
        );
        
        return $this->gridSearch($criteria);
    }
}
