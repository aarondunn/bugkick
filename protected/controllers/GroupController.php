<?php
/**
 * GroupController
 * @author f0t0n
 */
class GroupController extends Controller {
	
	// <editor-fold defaultstate="collapsed" desc="filters">
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	//</editor-fold>

	// <editor-fold defaultstate="collapsed" desc="accessRules">
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array(
				'allow',
				'actions'=>array(	// allow all users to perform next actions
				),
				'users'=>array('*'),
			),
			array(
				'allow',
				'actions'=>array(	// allow authenticated user to perform next actions
					'create', 'delete', 'edit'
				),
				'users'=>array('@'),
			),
			array(
				'allow',
				'actions'=>array(	// allow admin user to perform 'admin' and 'delete' actions
					'create', 'delete','edit'
				),
				'users'=>array('admin'),
			),
			array(
				'deny',
				'users'=>array('*'),	// deny all users
			),
		);
	}
	//</editor-fold>
	
	public function actionCreate() {
		$this->forward('group/edit');
	}

	public function actionEdit() {
		$form=new UserGroupForm();
		$this->performAjaxValidation($form, 'group-form');
		$group_id=(int)$this->request->getParam('group_id');
		$viewData['formTitle']=Yii::t(
			'main',
			empty($group_id) ? 'Create Group' : 'Edit Group'
		);
		$model=empty($group_id)
			? new UserGroup()
			: UserGroup::model()->currentCompany()->with(
				array(
					'users'=>array(
						'select'=>'users.user_id',
						'order'=>'users.name ASC'
					),
				)
			)->findByPk($group_id);
		$form->setAttributes($model->getAttributes());
		$attributes=$this->request->getPost('UserGroupForm');
		if(!empty($attributes)) {
			$form->setAttributes($attributes);
			if($form->validate()) {
				$model->setAttributes($form->getAttributes());
				$model->save();
				if($model->save()) {
                    //updating projects
                    $sqlParams=array(':group_id'=>$model->group_id);
                    $cmd=Yii::app()->db->createCommand();
                    $deleteSql='DELETE FROM {{project_by_group}}
                                WHERE group_id=:group_id';
                    $cmd->setText($deleteSql)->execute($sqlParams);
                    if(!empty($form->project_ids)) {
                        $getSqlValues=function($IDs) {
                            $values=array();
                            foreach($IDs as $id)
                                $values[]='('.(int)$id.', :group_id)';
                            return $values;
                        };
                        $insertSql='INSERT INTO {{project_by_group}} (project_id, group_id) VALUES'
                                .implode(',', $getSqlValues($form->project_ids));
                        $cmd->setText($insertSql)->execute($sqlParams);
                    }
                    /*
					$sqlParams=array(':group_id'=>$model->group_id);
					$cmd=Yii::app()->db->createCommand();
					$deleteSql='DELETE FROM {{user_by_group}} 
								WHERE group_id=:group_id';
					$cmd->setText($deleteSql)->execute($sqlParams);
					if(!empty($form->user_ids)) {
						$getSqlValues=function($IDs) {
							$values=array();
							foreach($IDs as $id)
								$values[]='(:group_id,'.(int)$id.')';
							return $values;
						};
						$insertSql='INSERT INTO {{user_by_group}} (group_id, user_id) VALUES'
								.implode(',', $getSqlValues($form->user_ids));
						$cmd->setText($insertSql)->execute($sqlParams);
					}
                    */
				}
			}
		}
        foreach($model->projects as $project)
            $form->project_ids[]=$project->project_id;

		$viewData['form']=$form;
		$viewData['formAction']=empty($model->group_id)
			? $this->createUrl('group/create')
			: $this->createUrl(
					'group/edit', 
					array('group_id'=>$model->group_id)
				);
		/*$viewData['projects']=Project::model()->currentCompany()->findAll(
			array(
				'select'=>'project_id, name',
				'order'=>'t.name ASC',
			)
		);
		$emptyProject=Project::model()->populateRecord(
			array('project_id'=>null, 'name'=>Yii::t('main','For all projects'))
		);
		array_unshift($viewData['projects'], $emptyProject);
		$viewData['users']=User::model()->currentCompany()->findAll(
			array(
				'select'=>'user_id, name',
				'order'=>'t.name ASC',
			)
		);
		$model->refresh();
		foreach($model->users as $user)
			$form->user_ids[]=$user->user_id;*/
		$this->renderPartial('editEx', $viewData);
	}
}