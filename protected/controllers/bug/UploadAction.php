<?php
/**
 * Action for uploading files to Box.net
 * Author: Alexey kavshirko@gmail.com
 * Date: 16.01.13
 * Time: 21:15
 */
Yii::import("xupload.actions.XUploadAction");

class UploadAction extends XUploadAction
{
    /**
     * Endpoint for downloading shared files http://www.box.net/s/<public_name>
     */
    const BOX_FILES_END_POINT = 'http://www.box.net/s/';

    /**
     * @var Box_Rest_Client Box.net API
     */
    protected $boxApi;

    /**
     * Current File model
     * @var File
     */
    protected $file;

    protected function initBoxApi()
    {
        $token = Yii::app()->session->get('boxAuthToken');
        if(empty($token))
            throw new CHttpException(400, 'Invalid request.');

        Yii::import('application.vendors.box.lib.Box_Rest_Client');
        $api_key = Yii::app()->params['box']['api_key'];
        try{
            $this->boxApi = new Box_Rest_Client($api_key);
        }
        catch(Box_Rest_Client_Exception $e) {
            throw new CHttpException(400, 'Invalid Box.net token');
        }
        $this->boxApi->auth_token = $token;
    }

    /**
     * Creates Box.net folder and returns ID
     * @return int
     * @throws CHttpException
     */
    protected function createBoxFolder()
    {
        $folder = new Box_Client_Folder();
        $folder->attr('name', Yii::app()->params['box']['folder_name']);
        $folder->attr('parent_id', 0);
        $folder->attr('share', false);
        $result = $this->boxApi->create($folder);
        if($result=='create_ok'){
            return $folder->attr('folder_id');
        }
        throw new CHttpException(500, "Could not create box folder.");
    }

    /**
     * Returns ID of Box.net folder
     * @return mixed
     * @throws CHttpException
     */
    protected function getBoxFolderID()
    {
        $indexFolder = $this->boxApi->folder(0);
        if(!empty($indexFolder)){
            $folders = $indexFolder->folder;
            if(!empty($folders) && is_array($folders)){
                foreach($folders as $folder){
                    if($folder->attr('name')==Yii::app()->params['box']['folder_name'])
                        return $folder->attr('id');
                }
            }
            return $this->createBoxFolder();
        }
        throw new CHttpException(500, "Could not get index folders list.");
    }

    /**
     * Uploads file to temporary directory
     *
     * @throws CHttpException
     */
    protected function handleUploading()
    {
        $this->initBoxApi();
        $boxFolderID = $this->getBoxFolderID();

        $ticketID = (int) Yii::app()->request->getParam('ticket_id');
        $this->subfolderVar = false;
        $this->secureFileNames = true;
        $this->init();
        $model = $this->formModel;
        $model->{$this->fileAttribute} = CUploadedFile::getInstance($model, $this->fileAttribute);
        if ($model->{$this->fileAttribute} !== null || empty($ticketID)) {
            $model->{$this->mimeTypeAttribute} = $model->{$this->fileAttribute}->getType();
            $model->{$this->sizeAttribute} = $model->{$this->fileAttribute}->getSize();
            $model->{$this->displayNameAttribute} = $model->{$this->fileAttribute}->getName();
            $model->{$this->fileNameAttribute} = $model->{$this->displayNameAttribute};

            if ($model->validate()) {

                $path = $this->getPath();

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }

                $model->{$this->fileAttribute}->saveAs($path . $model->{$this->fileNameAttribute});
                //chmod($path . $model->{$this->fileNameAttribute}, 0777);

                //upload to Box.net and delete file
                $file = new Box_Client_File($path . $model->{$this->fileNameAttribute},
                    $model->{$this->fileNameAttribute});
                $file->attr('folder_id', $boxFolderID);
                $res = $this->boxApi->upload($file,array(),true);
                if($res=='upload_ok'){

                    $shareLink = $this->boxApi->get('public_share',array(
                        'target'=>'file',
                        'target_id'=>$file->attr('id'),
                        'password'=>'',
                        'message'=>'',
                        'emails'=>''
                    ));

                    if(is_array($shareLink) && $shareLink['status']=='share_ok'){

                        $this->file = new File;
                        $this->file->box_file_id = $file->attr('id');
                        $this->file->name = $model->{$this->displayNameAttribute};
                        $this->file->public_name = $shareLink['public_name'];
                        $this->file->size = $model->{$this->sizeAttribute};
                        $this->file->ticket_id = $ticketID;
                        $this->file->user_id = Yii::app()->user->id;

                        if($this->file->save()){



                            $returnValue = $this->beforeReturn();
                            if ($returnValue === true) {
                                echo json_encode(array(array(
                                    "name" => $model->{$this->displayNameAttribute},
                                    "type" => $model->{$this->mimeTypeAttribute},
                                    "size" => $model->{$this->sizeAttribute},
                                    "url" => $this->getFileUrl($this->file->public_name),
                                    "thumbnail_url" => $this->getThumbnailUrl($this->file->public_name),
                                    "delete_url" => $this->getController()->createUrl($this->getId(), array(
                                        'YII_CSRF_TOKEN'=>Yii::app()->request->getCsrfToken(),
                                        "_method" => "delete",
                                        "file" =>$this->file->id,
                                    )),
                                    "delete_type" => "POST"
                                )));
                            } else {
                                echo json_encode(array(array("error" => $returnValue,)));
                                Yii::log("UploadAction: " . $returnValue, CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                            }
                        }
                        else{
                            echo json_encode(array(array("error" => $this->file->getErrors())));
                            Yii::log("UploadAction: " . CVarDumper::dumpAsString($this->file->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                        }

                    }
                    else{
                        echo json_encode(array(array("error" =>'An error has occurred while sharing uploaded file on Box.net, please try again.')));
                        Yii::log("UploadAction: An error has occurred while sharing uploaded file on Box.net.", CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                    }
                }
                else{
                    echo json_encode(array(array("error" => 'An error has occurred while uploading file to Box.net, please try again.')));
                    Yii::log("UploadAction: An error has occurred while uploading file to Box.net", CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                }
            } else {
                echo json_encode(array(array("error" => $model->getErrors($this->fileAttribute),)));
                Yii::log("UploadAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
            }
        } else {
            throw new CHttpException(500, "Could not upload file");
        }
    }

    /**
     * Removes temporary file from its directory and from the session
     *
     * @return bool Whether deleting was meant by request
     */
    protected function handleDeleting()
    {
        if (isset($_GET["_method"]) && $_GET["_method"] == "delete") {
            $success = false;
            if ($_GET["file"][0] !== '.' && Yii::app()->user->hasState($this->stateVariable)) {
                // pull our userFiles array out of state and only allow them to delete
                // files from within that array
                $userFiles = Yii::app()->user->getState($this->stateVariable, array());

                if(isset($userFiles[$_GET["file"]])){
                    $success = $this->deleteFile($_GET["file"]);
                    if ($success) {
                        unset($userFiles[$_GET["file"]]); // remove it from our session and save that info
                        Yii::app()->user->setState($this->stateVariable, $userFiles);
                    }
                }
            }
            echo json_encode($success);
            return true;
        }
        return false;
    }

    /**
     * We store info in session to make sure we only delete files we intended to
     * Other code can override this though to do other things with state, thumbnail generation, etc.
     * @since 0.5
     * @return boolean|string Returns a boolean unless there is an error, in which case it returns the error message
     */
    protected function beforeReturn()
    {
        $path = $this->getPath();

        // Now we need to save our file info to the user's session
        $userFiles = Yii::app( )->user->getState( $this->stateVariable, array());

        $userFiles[$this->file->id] = array(
            "path" => $path.$this->formModel->{$this->fileNameAttribute},
            //the same file or a thumb version that you generated
            "thumb" => $path.$this->formModel->{$this->fileNameAttribute},
            "filename" => $this->formModel->{$this->fileNameAttribute},
            'size' => $this->formModel->{$this->sizeAttribute},
            'mime' => $this->formModel->{$this->mimeTypeAttribute},
            'name' => $this->formModel->{$this->displayNameAttribute},
        );
        Yii::app( )->user->setState( $this->stateVariable, $userFiles );

        return true;
    }

    /**
     * Returns the file's relative URL path
     * @return string
     */
    protected function getPublicPath()
    {
        return self::BOX_FILES_END_POINT;
    }

    /**
     * A stub to allow overrides of thumbnails returned
     * @since 0.5
     * @param string $publicName
     * @return string thumbnail name (if blank, thumbnail won't display)
     */
    public function getThumbnailUrl($publicName)
    {
        return $this->getPublicPath() . $publicName;
    }

    /**
     * Deletes file from user's Box.net account
     * @param int $fileID file ID on Bugkick.com
     * @return bool
     * @throws CHttpException
     */
    public function deleteFile($fileID)
    {
        $this->initBoxApi();
        $file = File::model()->findByPk($fileID);
        if(empty($file))
            throw new CHttpException(404, 'File was not found.');

        $result = $this->boxApi->get('delete', array(
            'target'=>'file',
            'target_id'=>$file->box_file_id,
        ));

        if(is_array($result) && $result['status']=='s_delete_node'){
            return $file->delete();
        }
        return false;
    }
}
