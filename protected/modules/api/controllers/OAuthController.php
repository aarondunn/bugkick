<?php
/**
 * Handles OAuth requests
 *
 * @author f0t0n
 */
class OAuthController extends Controller {

    public function actionGetRequestToken() {
        $this->logRequest();
        echo sha1($_GET['oauth_signature']);
    }

    public function actionAuthorizeToken() {
        $this->logRequest();
    }

    public function actionGetAccessToken() {
        $this->logRequest();
        echo 100502;
    }

    protected function logRequest() {
        $get = var_export($_GET, true);
        $post = var_export($_POST, true);
        $time = date(DATE_W3C);
        file_put_contents(
            Yii::getPathOfAlias('webroot.log') . '/oauth.log',
            "$time:\nGET:\n$get\n\nPOST:\n$post\n----\n\n",
            FILE_APPEND
        );
    }
}