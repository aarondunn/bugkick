<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.11.12
 * Time: 18:35
 */

class PeopleAction extends Action
{
    const PAGE_SIZE = 50;

	protected $project;

	protected function init() {

        $this->controller->layout = '//layouts/column1';

		$this->project= Project::getCurrent();
        if(empty($this->project))
            throw new CHttpException(404, 'Please choose project first.');

        $user = User::current();
        if(empty($user)
            || $user->getStatusInCompany(Company::current())
                != User::STATUS_ACTIVE) {
            throw new CHttpException(403, 'Action forbidden.');
        }
	}

	public function run() {
		$this->init();

        $criteria = new CDbCriteria();
        $criteria->with = array(
            'project'=>array(
                'condition'=>'project.project_id=:project_id',
                'params'=>array(
                    ':project_id'=>$this->project->project_id,
                ),
                'together'=>true,
            )
        );
        $dataProvider = new CActiveDataProvider('User',array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>self::PAGE_SIZE,
            ),
        ));
        $this->controller->render('people', array(
            'dataProvider'=>$dataProvider
        ));
	}
}