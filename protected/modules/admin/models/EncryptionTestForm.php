<?php
/**
 * EncryptionTestForm
 *
 * @author f0t0n
 */
class EncryptionTestForm extends FormModel {

    public $password;
    public $workFactor = 8;
    public $hash;
    public $hashTime;
    public $checkTime;
    public $testHash;
    
    protected $minWorkFactor = 4;
    protected $maxWorkFactor = 15;
    protected $sessHashKey;
    protected $sessPasswordKey;
    
    public function init() {
        $this->sessHashKey = __CLASS__ . '#passwordHash';
        $this->sessPasswordKey = __CLASS__ . '#password';
    }
    
    public function rules() {
        return array(
            array('password, workFactor', 'required'),
            array('workFactor', 'numerical',
                'min'=>4, 'max'=>31, 'integerOnly'=>true, 'allowEmpty'=>false),
            array('hash', 'length', 'max'=>60, 'min'=>60, 'allowEmpty'=>true),
            array('testHash', 'boolean', 'trueValue'=>1, 'falseValue'=>0),
        );
    }
    
    public function attributeLabels() {
        return array(
            'password'=>'Password',
            'workFactor'=>'Work Factor (from 4 to 31)',
            'hash'=>'Hash',
            'hashTime'=>'Hash Time (ms)',
            'checkTime'=>'Check Time (ms)',
        );
    }
    
    public function getMinWorkFactor() {
        return $this->minWorkFactor;
    }
    
    public function getMaxWorkFactor() {
        return $this->maxWorkFactor;
    }
    
    public function test() {
        if(empty($this->testHash) && !empty($this->hash)) {
            $this->testCheck();
        } else {
            $this->testHash();
        }
    }
    
    public function testHash() {
        $beforeHash = microtime(true)   ;
        $this->hashPassword();
        $this->hashTime = $this->getMs(microtime(true) - $beforeHash);
    }
    
    public function testCheck() {
        $beforeCheck = microtime(true);
        try {
            $this->checkPassword();
            $this->checkTime = $this->getMs(microtime(true) - $beforeCheck);
        } catch(Exception $ex) {
            $this->addError('hash', $ex->getMessage());
        }
    }
    
    protected function hashPassword() {
        $user = new User();
        $salt = $user->generateSalt();
        $this->hash = Bcrypt::hash(
            $this->password . $salt, $this->workFactor);
    }
    
    protected function checkPassword() {
        Bcrypt::check($this->password, $this->hash);
    }
    
    protected function getMs($s) {
        return $s * 1000;
    }
}