<?php

/**
 * minScript Controller.
 *
 * Serve combined, minified and compressed files with cache headers.
 *
 * @package ext.minScript.controllers
 * @author total-code
 * @copyright Copyright &copy; 2012 total-code
 * @license BSD 3-clause
 * @link http://bitbucket.org/totalcode/minscript
 * @version 1.1.0
 */
class ExtMinScriptController extends CExtController {
	/**
	 * Serve files.
	 */
	public function actionServe() {
		require (dirname(dirname(__FILE__)) . '/vendors/minify/min/index.php');
	}

	/**
	 * Ensure that everything is prepared before we execute the serve action.
	 * @param CFilterChain $filterChain Instance of CFilterChain.
	 */
	public function filterValidateServe($filterChain) {
		header('X-Powered-By:');
		header('Pragma:');
		header('Expires:');
		header('Cache-Control:');
		header('Last-Modified:');
		header('Etag:');
		@ob_end_clean();
		$get = array();
		if(isset($_GET['g'])) {
			$get['g'] = $_GET['g'];
		}
		if(isset($_GET['f']) && !empty(Yii::app() -> minScript -> allowDirs)) {
			$get['f'] = urldecode($_GET['f']);
		}
		if(isset($_GET['debug'])) {
			$get['debug'] = '';
		}
		if(isset($_GET['lm']) && ctype_digit((string)$_GET['lm'])) {
			$get[$_GET['lm']] = '';
		}
		$_GET = $get;
		$_SERVER['QUERY_STRING'] = urldecode(http_build_query($get, '', '&'));
		if(isset(Yii::app() -> log)) {
			foreach(Yii::app()->log->routes as $route) {
				if($route instanceof CWebLogRoute) {
					$route -> enabled = false;
				}
			}
		}
		$filterChain -> run();
	}

	/**
	 * Execute filters.
	 * @return array Filters to execute.
	 */
	public function filters() {
		return array('validateServe + serve', );
	}

}
