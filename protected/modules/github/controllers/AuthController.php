<?php
/**
 * AuthController
 *
 * @author f0t0n
 */
class AuthController extends GithubController {

    /**
     * GitHub OAuth Step 1
     * @param int $project_id
     */
    public function actionIndex() {
        $project_id = $this->request->getParam('project_id');
        $this->session->add(GitHubAuth::PROJECT_ID_SESS_KEY, $project_id);
        $this->session->add(GitHubAuth::RETURN_URL_SESS_KEY,
                $this->request->urlReferrer);
        $this->redirect(
            GitHubAuth::GITHUB_AUTH_URL
            . '?client_id=' . GitHubClient::getGitHubParam('clientID')
            . '&scope=' . GitHubAuth::GITHUB_ACCESS_SCOPE
        );
    }

    /**
     * GitHub OAuth Step 2
     */
    public function actionCallback() {
        $code = $this->request->getParam('code');
        $state = $this->request->getParam('state');
        $user = User::current();
        $project_id = $this->session->get(GitHubAuth::PROJECT_ID_SESS_KEY);
        $project = empty($project_id)
            ? null
            : Project::model()->findByPk($project_id);
        if(empty($code) || empty($user)) {
            $this->authFailed();
        }
        try {
            $this->completeAuth($code, $state, $user, $project);
            if(empty($project)) {
                $this->authSucceed($this->getReturnUrl());
            } else {
                $this->authSucceed($this->createUrl('/github/repo/connect'));
            }
        } catch(GithubException $e) {
            $this->authFailed();
        } catch(CException $e) {
            $this->authFailed();
        }
    }

    /**
     *
     * @param User $user
     * @param Project $project
     * @throws CException
     * @throws GithubException
     */
    protected function completeAuth($code, $state,
                User $user, Project $project = null) {
        $gitHubClient = new GitHubClient();
        $authToken = $this->getAuthToken($gitHubClient, $code, $state);
        $user->github_auth_token = $authToken;
        $gitHubClient->setAuthToken($authToken);
        $gitHubUser = $this->createGitHubUser($user, $gitHubClient);
        if(!$gitHubUser->save()) {
            $this->authFailed();
        }
        $user->github_user_id = $gitHubUser->id;
        if(!$user->save()) {
            $gitHubUser->delete();
            $this->authFailed();
        }
        /*if(!empty($project)) {
            $project->github_user_id = $gitHubUser->id;
            if(!$project->save()) {
                $this->authFailed();
            }
        }*/
    }

    /**
     * @param User $user
     * @param GitHubClient $gitHubClient
     * @return \GithubUser
     */
    protected function createGitHubUser(
            User $user, GitHubClient $gitHubClient) {
        $gitHubUser = empty($user->githubUser)
            ? new GithubUser()
            : $user->githubUser;
        $attributes = $gitHubClient->getUser();
        $gitHubUser->is_active = 1;
        $gitHubUser->login = $attributes['login'];
        $gitHubUser->html_url = $attributes['html_url'];
        $gitHubUser->avatar_url = $attributes['avatar_url'];
        return $gitHubUser;
    }

    protected function authSucceed($redirectUrl) {
        //Yii::app()->user->setFlash('success', GitHubAuth::AUTH_MESSAGE_SUCCESS);
        $this->redirect($redirectUrl);
    }

    protected function authFailed() {
        Yii::app()->user->setFlash('error', GitHubAuth::AUTH_MESSAGE_FAIL);
        $this->redirect($this->getReturnUrl());
    }

    protected function getReturnUrl() {
        return $this->session->get(GitHubAuth::RETURN_URL_SESS_KEY,
            $this->request->getBaseUrl(true));
    }

    /**
     *
     * @param type $code
     * @param type $state
     * @return string GitHub authentication token.
     * @throws GithubException
     */
    protected function getAuthToken(GitHubClient $gitHubClient, $code, $state) {
        $post = array(
            'client_id'=>GitHubClient::getGitHubParam('clientID'),
            'client_secret'=>GitHubClient::getGitHubParam('clientSecret'),
            'code'=>$code,
            'state'=>$state,
        );
        $response = $gitHubClient->post(
            GitHubAuth::GITHUB_ACCESS_TOKEN_URL, $post, false);
        return empty($response['access_token'])
            ? null
            : $response['access_token'];
    }
}