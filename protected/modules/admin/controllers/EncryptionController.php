<?php
/**
 * EncryptionController
 *
 * @author f0t0n
 */
class EncryptionController extends AdminController {

    public function actionTest() {
        $model = new EncryptionTestForm();
        $model->testHash = (int)$this->request->getParam('testHash', 1);
        $formID = 'encryption-test-form';
        $this->performAjaxValidation($model, $formID);
        $attributes = $this->request->getPost(get_class($model));
        if(!empty($attributes)) {
            $model->setAttributes($attributes);
            if($model->validate()) {
                $model->test();
            } else {
                //var_dump($model->getErrors());
            }
        }
        $renderMethod = $this->request->isAjaxRequest
            ? 'renderPartial'
            : 'render';
        $this->{$renderMethod}('test', array(
            'model'=>$model,
            'formID'=>$formID
        ));
    }
}