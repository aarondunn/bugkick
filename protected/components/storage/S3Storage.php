<?php
Yii::import('ext.s3.S3');
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.05.12
 * Time: 0:17
 */
class S3Storage extends BaseStorage implements S3Storable{

    private $_s3;
   	public $accessKey; // AWS Access key
   	public $secretKey; // AWS Secret key
    public $useSSL; //Use SSL
    public $endPoint = 's3.amazonaws.com';
   	public $bucket;
   	public $lastError="";

    const PROFILE_BUCKET = 'bugkick_profile';
    const COMPANY_BUCKET = 'bugkick_company_logo';
    const COMPANY_TOP_BUCKET = 'bugkick_company_top_logo';
    const PROJECT_BUCKET = 'bugkick_project';

    public function init()
    {
        parent::init();
        $this->_s3 = new S3($this->accessKey, $this->secretKey, $this->useSSL, $this->endPoint);
    }

    /**
     * Uploads file to Amazon S3
     * @param string $bucket name of the bucket
     * @param string $fileName name of the file
     * @param string $filePath full path of the file
     * @param string $acl S3 access level
     * @return string : boolean path to file
     */
    public function upload($bucket, $fileName, $filePath,  $acl = S3::ACL_PUBLIC_READ){
        //create a new bucket
        $this->_s3->putBucket($bucket, $acl);

        //move the file
        if ($this->_s3->putObjectFile($filePath, $bucket, $fileName, $acl)) {
            return 'http://'.$bucket.'.'.$this->endPoint.'/'.$fileName;
        }
        else{
            return false;
        }
    }

    /**
     * Return path to file based on file name and bucket name
     * @param string $fileName
     * @param string $bucket
     * @return string path to file
     */
    public function getFilePath($fileName, $bucket)
    {
        return 'https://'. $bucket . '.' . $this->endPoint . '/' . $fileName;
    }

}