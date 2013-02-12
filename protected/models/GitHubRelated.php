<?php
Yii::import('application.modules.github.components.*');
Yii::import('application.modules.github.components.client.*');

/**
 * GitHubRelated
 *
 * @author f0t0n
 */
class GitHubRelated extends CActiveRecord {

    /**
     *
     * @var GitHubClient
     */
    protected static $gitHubClient;

    /**
     *
     * @return \GitHubClient
     */
    protected function getGitHubClient() {
        if(empty(self::$gitHubClient)) {
            self::$gitHubClient = new GitHubClient(User::current());
        }
        return self::$gitHubClient;
    }
}