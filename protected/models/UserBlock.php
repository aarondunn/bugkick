<?php

/**
 * This is the model class for table "{{user_block}}".
 *
 * The followings are the available columns in table '{{user_block}}':
 * @property integer $user_id
 * @property string $user_ip
 * @property string $block_to
 * @property integer $id
 */
class UserBlock extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserBlock the static model class
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
		return '{{user_block}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_ip, block_to', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('user_ip', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_ip, block_to, id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'user_ip' => 'User Ip',
			'block_to' => 'Block To',
			'id' => 'ID',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('block_to',$this->block_to,true);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        public function isBlock($ip){
            $command = Yii::app()->db->createCommand();
            $res=$command->select('user_ip , first_entry, count_entry, block_to')
                    ->from('bk_user_block')
                    ->where('user_ip=:user_ip', array(':user_ip' => $ip))->queryRow();
            if($res){
                if($res['count_entry'] > 20 && $res['block_to'] > time()){
                   return true; 
                }else if($res['count_entry'] > 20 && $res['block_to'] <= time()){
                    $this->deleteUser($res['user_ip']);
                }
                if($res['first_entry'] < time()){
                    $this->deleteUser($res['user_ip']);
                }
                return false;
            }else{
                return false;
            }
        }
        
        public function addBlock($ip){
            $res = $this->selectUser($ip);
            if($res){
                if($res >= 20){
                    $this->updateUser($ip,$res,true);
                }else{
                    $this->updateUser($ip,$res);
                }
            }else{
                $this->insertUser($ip);
            }
        }
        
        public function insertUser($ip) {
            $command = Yii::app()->db->createCommand();
            $command->insert('bk_user_block',
                    array(
                        'user_ip' => $ip,
                        'count_entry' => '1',
                        'first_entry' => time()+60*60
                        )
                );
        }
        
        public function updateUser($ip, $count_entry, $block=false){
            $command = Yii::app()->db->createCommand();
            if($block){
                $updateField = array('count_entry' => 1 + $count_entry,'block_to' => time()+60*60);
            }else{
                $updateField = array('count_entry' => 1 + $count_entry);
            }
            $command->update('bk_user_block', 
                    $updateField,
                    'user_ip=:user_ip',
                    array(':user_ip' => $ip)
                );
        }
        
        public function selectUser($ip){
            $command = Yii::app()->db->createCommand();
            $res=$command->select('count_entry')
                    ->from('bk_user_block')
                    ->where('user_ip=:user_ip', array(':user_ip' => $ip))->queryRow();
            if($res){
                return $res['count_entry'];
            }else{
                return false;
            }
        }
        public function deleteUser($ip){
            $command = Yii::app()->db->createCommand();
            $command->delete('bk_user_block', 'user_ip=:user_ip', array(':user_ip'=>$ip));
        }
}