<?php
/**
 * HelloController
 *
 * @author f0t0n
 */
class RepoController extends GithubController {

    const REPOSITORIES_CACHE_KEY = 'RepoController#repositories';
    const PAGE_LINKS_CACHE_KEY = 'RepoController#page_links';
    const ITEMS_PER_PAGE = 30;
    const REPOSITORIRES_CACHE_EXPIRE = 120;
	
    /** @var User */
    protected $user;
    /** @var Project */
    protected $project;
    /** @var GitHubClient */
    protected $gitHubClient;

    public function init() {
        parent::init();
        $this->user = User::current();
        $projectId = $this->request->getParam('project_id');
        if($projectId === null) {
            $projectId = $this->session->get(GitHubAuth::PROJECT_ID_SESS_KEY);
        } else {
            $this->session->add(GitHubAuth::PROJECT_ID_SESS_KEY, $projectId);
        }
        $this->project = Project::model()->findByPk($projectId);
        $this->gitHubClient = new GitHubClient($this->user);
    }

    protected function beforeAction($action) {
        $res = parent::beforeAction($action)
            && !empty($this->user)
            && !empty($this->project);
        if($res) {
            if(empty($this->user->githubUser)
                    || !$this->user->githubUser->is_active) {
                $this->redirectToGitHubAuth();
            }
            $this->gitHubClient->setAuthToken($this->user->github_auth_token);
        } else {
            Yii::app()->user->setFlash('error', 'Action is not permitted.');
            $this->redirect($this->getReturnUrl());
        }
        return $res;
    }

    protected function getReturnUrl() {
        return $this->session->get(
            GitHubAuth::RETURN_URL_SESS_KEY,
            $this->request->getBaseUrl(true)
        );
    }
    
	public function actionConnect() {
        $page = (int) $this->request->getParam('page', 1);
        $repositories = $this->getRepositories($page, self::ITEMS_PER_PAGE);
        $pageLinks = $this->getPageLinks($page, self::ITEMS_PER_PAGE);
        $formID = 'connect-repo-form';
        $model = new ConnectRepoForm();
        $model->setAttributes($this->project->getAttributes());
        $this->performAjaxValidation($model, $formID);
        $attributes = $this->request->getPost(get_class($model));
        if(!empty($attributes)) {
            $model->setAttributes($attributes);
            if($model->validate()) {
                $this->project->setAttributes($model->getAttributes());
                $this->project->github_user_id = $this->user->githubUser->id;
                $this->project->save();
            }
        }
        $renderMethod = $this->request->isAjaxRequest
            ? 'renderPartial'
            : 'render';
		$this->{$renderMethod}('connect', array(
            'formID'=>$formID,
            'model'=>$model,
            'project'=>$this->project,
            'repositories'=>$repositories,
            'links'=>implode(
                '&nbsp;<b style="color: #08C;">&middot;</b>&nbsp;', $pageLinks),
            'dataProvider'=>new CArrayDataProvider($repositories, array(
                'pagination'=>array(
                    'pageSize'=>self::ITEMS_PER_PAGE,
                )
            )),
        ));
	}

    protected function getPageLinks($page = 1,
            $per_page = self::ITEMS_PER_PAGE) {
        $pageLinks = Yii::app()->cache->get(
            $this->getPageLinksCacheKey($page, $per_page));
        $links = array();
        foreach($pageLinks as $name => $number) {
            if($number !== null) {
                $url = $this->createUrl('/github/repo/connect', array(
                    'project_id'=>$this->project->project_id,
                    'page'=>$number,
                ));
                $links[] = CHtml::link($name, $url, array());
            }
        }
        return $links;
    }

    protected function getPageLinksCacheKey($page,
            $per_page = self::ITEMS_PER_PAGE) {
        return self::PAGE_LINKS_CACHE_KEY
            . '#page=' . $page
            . '#per_page=' . $per_page
            . '#github_user_id=' . $this->user->githubUser->id;
    }

    protected function getRepositoriesCacheKey($page,
            $per_page = self::ITEMS_PER_PAGE) {
        return self::REPOSITORIES_CACHE_KEY
            . '#page=' . $page
            . '#per_page=' . $per_page
            . '#github_user_id=' . $this->user->githubUser->id;
    }

    /**
     *
     * @return array 
     */
    protected function getRepositories($page = 1,
            $per_page = self::ITEMS_PER_PAGE) {
        try {
            $cache = Yii::app()->cache;
            $keyRepo = $this->getRepositoriesCacheKey($page, $per_page);
            $keyLinks = $this->getPageLinksCacheKey($page, $per_page);
            if(($repositories = $cache->get($keyRepo)) === false) {
                $repositories = $this->gitHubClient->getRepositories(
                    $page, $per_page);
                $pageLinks = $this->gitHubClient->getPageLinks();
                $cache->set($keyRepo, $repositories,
                    self::REPOSITORIRES_CACHE_EXPIRE);
                $pageLinksDependency = new CExpressionDependency(
                    "isset(Yii::app()->cache['{$keyRepo}'])");
                $cache->set($keyLinks, $pageLinks,
                    self::REPOSITORIRES_CACHE_EXPIRE, $pageLinksDependency);
            }
            return $repositories;
        } catch(GithubException $e) {
            if($e->getCode() == GithubException::UNAUTHORIZED) {
                $this->user->githubUser->is_active = 0;
                $this->user->githubUser->save();
                $this->redirectToGitHubAuth();
            }
        } catch(CException $e) {
            Yii::app()->user->setFlash('error',
                "Can't retrieve the data from GitHub.");
        } catch(Exception $e) {
            Yii::app()->user->setFlash('error',
                'An error occured. Please use "Feedback" button to report it.');
        }
        return array();
    }

    protected function redirectToGitHubAuth() {
        $this->redirect($this->createUrl('/github/auth', array(
            'project_id'=>$this->project->project_id
        )));
    }
}