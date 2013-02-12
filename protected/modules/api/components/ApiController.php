<?php
/**
 * ApiController
 *
 * @author f0t0n
 */
class ApiController extends Controller {
    
    protected function beforeAction($action) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type');
        return parent::beforeAction($action);
    }
}