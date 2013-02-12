<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 17.07.12
 * Time: 15:55
 */
class MaintenanceCommand extends Command
{
    public function actionIndex()
    {
        print 'Index Action.';
    }

    /**
     * This action generates previews and uploads them to S3
     */
    public function actionMoveImagesToS3()
    {
        Yii::import('ext.EWideImage.EWideImage');

        $users = User::model()->findAll();
        if (!empty($users) && is_array($users)){
            foreach($users as $user){
                $image = $user->profile_img;
                if(!empty($image)){
                    $imageFolder = 'images/profile_img/';
                    $imagePath = $imageFolder . $image;
                    if(is_file($imagePath)){
                        //preview 81px*81px
                        $thumbName = '81_81_'.$image;
                        $thumbPath = $imageFolder . $thumbName;
                        EWideImage::load($imagePath)->resize(81, 81)->saveToFile($thumbPath);
                        $this->handleImageUpload('user', $thumbName, $thumbPath);

                        //preview 31px*31px
                        $thumbName = '31_31_'.$image;
                        $thumbPath = $imageFolder . $thumbName;
                        EWideImage::load($imagePath)->resize(31, 31)->saveToFile($thumbPath);
                        $this->handleImageUpload('user', $thumbName, $thumbPath);
                    }
                }
                unset($image);
            }
        }

        $companies = Company::model()->findAll();
        if(!empty($companies) && is_array($companies)){
            foreach($companies as $company){
                $image = $company->company_top_logo;
                if(!empty($image)){
                    $imageFolder = 'images/company_top_logo/';
                    $imagePath = $imageFolder . $image;
                    if (is_file($imagePath)) {
                        //preview 132px*33px
                        $thumbName = '132_33_'.$image;
                        $thumbPath = $imageFolder . $thumbName;
                        EWideImage::load($imagePath)->resize(132, 33)->saveToFile($thumbPath);
                        $this->handleImageUpload('company', $thumbName, $thumbPath);
                    }
                }
                unset($image);
            }
        }

        $projects = Project::model()->findAll();
        if(!empty($projects) && is_array($projects)){
            foreach($projects as $project){
                $image = $project->logo;
                if(!empty($image)){
                    $imageFolder = 'images/project_logo/';
                    $imagePath = $imageFolder . $image;
                    if (is_file($imagePath) && substr_count($image, 'defaults/')<1) {
                        //preview 70px*70px
                        $thumbName = '70_70_'.$image;
                        $thumbPath = $imageFolder . $thumbName;
                        EWideImage::load($imagePath)->resize(70, 70)->saveToFile($thumbPath);
                        $this->handleImageUpload('project', $thumbName, $thumbPath);
                    }
                }
                unset($image);
            }
        }
    }

    /**
     * Handles upload image to s3
     * @param $for = 'user', 'company', 'project'
     * @param string $fileName - image name like 123.jpg
     * @param $filePath - full path to image like images/user/123.jpg
     * @return string path to image
     * @throws CHttpException
     */
    protected function handleImageUpload($for, $fileName, $filePath)
    {
         //upload to s3
         if($for=='user')
             $bucket = S3Storage::PROFILE_BUCKET;
         elseif($for=='company')
             $bucket = S3Storage::COMPANY_TOP_BUCKET;
         elseif($for=='project')
             $bucket = S3Storage::PROJECT_BUCKET;
         else
             return null;

         $s3FilePath = Storage::get('s3')->upload(
             $bucket,
             $fileName,
             $filePath
         );
         @unlink($filePath);
         if (!empty($s3FilePath))
             return $s3FilePath;
    }

    /**
     * This action fills bug.bk_user_set and bug.bk_label_set
     * with data from bk_bug_by_user and bk_bug_by_label
     */
    public function actionFillUserAndLabelSets()
    {
        $bugs = Bug::model()->resetScope()->with('user','label')->findAll();
        if (!empty($bugs) && is_array($bugs)){
            foreach($bugs as $bug){
                $users = $bug->user;
                $labels = $bug->label;
                $this->fillAttribute($bug, 'user', $users);
                $this->fillAttribute($bug, 'label', $labels);
                $bug->save();
            }
        }
    }

    protected function fillAttribute(&$model, $attribute, $values)
    {
        if(!empty($values) && is_array($values)){
            switch ($attribute) {
                case 'user':
                        foreach($values as $value){
                            $IDs[] = $value->user_id;
                        }
                        $model->user_set = CJSON::encode($IDs);
                    break;
                case 'label':
                        foreach($values as $value){
                            $IDs[] = $value->label_id;
                        }
                        $model->label_set = CJSON::encode($IDs);
                    break;
            }
        }
    }

}