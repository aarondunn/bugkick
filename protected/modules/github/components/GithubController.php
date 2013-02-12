<?php
/**
 * GithubController
 *
 * @author f0t0n
 */
class GithubController extends Controller {

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'users'=>array('@'),
            ),
            array('allow',
                'expression'=>function($user, $rule) {
                    $company = Company::model()->findByPk(Company::current());
                    return !$user->isGuest &&
                        $company->isGitHubIntegrationAvailable();
                },
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}