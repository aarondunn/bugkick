<?php

/**
 * minScript Application Component.
 *
 * Takes care of converting the groupMap and generating URLs.
 *
 * @package ext.minScript.components
 * @author total-code
 * @copyright Copyright &copy; 2012 total-code
 * @license BSD 3-clause
 * @link http://bitbucket.org/totalcode/minscript
 * @version 1.1.0
 *
 * @property array $groupMap Returns the minScript groupMap.
 */
class ExtMinScript extends CApplicationComponent {

	/**
	 * @var boolean Whether scripts should be displayed in debug mode. If you set this to true, scripts won't
	 * be minified or cached and will be populated with line numbers. Defaults to false.
	 */
	public $scriptDebug = false;

	/**
	 * @var array Restrict access to files within the folders defined in this array. This doesn't apply to
	 * files inside the groupMap property. IMPORTANT: If not configured properly the content of sensitive
	 * files could be displayed. Defaults to an empty array which turns of direct file access.
	 */
	public $allowDirs = array();

	/**
	 * @var string ID of the minScript Controller as defined in the controllerMap property.
	 */
	protected $_controllerID;

	/**
	 * @var string Minify root directory.
	 */
	protected $_minifyDir;

	/**
	 * @var boolean Whether groupMap is read-only.
	 */
	protected $_readOnlyGroupMap = false;

	protected $_groupMap = array();

	/**
	 * Initialize minScript Component and convert groupMap.
	 * @throws CException if minScript runtime folder not writable.
	 * @throws CException if groupsConfig not writable.
	 * @throws CException if minScript Controller not defined in the controllerMap property.
	 */
	public function init() {
		parent::init();
		$minifyDir = dirname(dirname(__FILE__)) . '/vendors/minify/min';
		$this -> _minifyDir = $minifyDir;
		if (!extension_loaded('apc')) {
			$cachePath = Yii::app() -> runtimePath . '/minScript/cache';
			if (!is_dir($cachePath)) {
				mkdir($cachePath, 0777, true);
			} else if (!is_writable($cachePath)) {
				throw new CException('ExtMinScript: ' . $cachePath . ' is not writable.');
			}
		}
		if (!is_writable($minifyDir . '/groupsConfig.php')) {
			throw new CException('ExtMinScript: ' . $minifyDir . '/groupsConfig.php is not writable.');
		}
		$controllerMap = Yii::app()->controllerMap;
		foreach($controllerMap as $controllerID => $controllerClass) {
			if(is_array($controllerClass)) {
				if(isset($controllerClass['class']) && strstr($controllerClass['class'], 'ExtMinScriptController') !== false) {
					$this -> _controllerID = $controllerID;
					break;
				}
			} else {
				if(strstr($controllerClass, 'ExtMinScriptController') !== false) {
					$this -> _controllerID = $controllerID;
					break;
				}
			}
		}
		if(empty($this -> _controllerID)) {
			throw new CException('ExtMinScript: The minScript Controller needs to be defined in the controllerMap property.');
		}
		$this -> _processGroupMap();
		$this -> _readOnlyGroupMap = true;
	}

	/**
	 * Get the minScript groupMap.
	 * @return array The minScript groupMap.
	 */
	public function getGroupMap() {
		return $this -> _groupMap;
	}

	/**
	 * Set the minScript groupMap. This method needs to be executed before the component is initialized.
	 * @param array $groupMap Array containing groups with files that need to be served. Files with asterisks
	 * in their filenames will be skipped and logged.
	 */
	public function setGroupMap($groupMap) {
		if (!$this -> _readOnlyGroupMap) {
			$this -> _groupMap = $groupMap;
		}
	}

	/**
	 * Process groupMap and generate groupsConfig
	 */
	protected function _processGroupMap() {
		$groupMap = $this -> getGroupMap();
		$groupsConfig = '&lt;?php return array(';
		//Groups
		foreach ($groupMap as $group => $items) {
			if ($groupsConfig == '&lt;?php return array(') {
				$groupsConfig .= '\'' . $group . '\'=>array(';
			} else {
				$groupsConfig .= '),\'' . $group . '\'=>array(';
			}
			//Files
			foreach ($items as $index => $path) {
				$filename = basename($path);
				if (strpos($filename, '*') !== false) {
					Yii::log('No asterisks in filename, skipping file ' . $path, CLogger::LEVEL_WARNING, 'ext.minScript.components.ExtMinScript');
					unset($groupMap[$group][$index]);
					continue;
				}
				$groupsConfig .= '\'' . $path . '\',';
			}
		}
		if ($groupsConfig == '&lt;?php return array(') {
			$groupsConfig .= ');';
		} else {
			$groupsConfig .= '));';
		}
		if ($this -> _compareGroupsConfig($groupsConfig)) {
			$this -> _writeGroupsConfig($groupsConfig);
		}
		$this -> setGroupMap($groupMap);
	}

	/**
	 * Generate Yii's scriptMap from supplied minScript groups or file paths. The supplied groups or file paths
	 * are used to generate one URL which gets mapped to the files.
	 * @param mixed $generationValues Group names or file paths for scriptMap generation. This parameter accepts
	 * a string for a single value or an array for multiple values. Group names and file paths can be mixed
	 * inside the array. Whether the passed values are group names or file paths is automatically detected. For
	 * file paths to work the allowDirs property has to be configured.
	 */
	public function generateScriptMap($generationValues) {
		$groupMap = $this -> getGroupMap();
		$paths = array();
		if (!empty($generationValues)) {
			$generationValues = (array)$generationValues;
			foreach($generationValues as $generationValue) {
				if (isset($groupMap[$generationValue])) {
					$paths = array_merge($paths, $groupMap[$generationValue]);
				} else {
					$paths[] = $generationValue;
				}
			}
		}
		$minScriptUrl = $this -> generateUrl($generationValues);
		foreach($paths as $path) {
			$filename = basename($path);
			Yii::app() -> clientScript -> scriptMap[$filename] = $minScriptUrl;
		}
	}

	/**
	 * Generate URL from minScript groups or file paths.
	 * @param mixed $generationValues Group names or file paths for URL generation. This parameter accepts a
	 * string for a single value or an array for multiple values. Group names and file paths can be mixed
	 * inside the array. Whether the passed values are group names or file paths is automatically detected.
	 * For file paths to work the allowDirs property has to be configured.
	 * @return string URL to minScript Controller.
	 */
	public function generateUrl($generationValues) {
		$filemtimes = array();
		$params = array();
		$groups = array();
		$files = array();
		$paths = array();
		$groupMap = $this -> getGroupMap();
		if(!empty($generationValues)) {
			$generationValues = (array)$generationValues;
			foreach($generationValues as $generationValue) {
				if (isset($groupMap[$generationValue])) {
					$groups[] = $generationValue;
					$paths = array_merge($paths, $groupMap[$generationValue]);
				} else {
					$files[] = $generationValue;
					$paths[] = $generationValue;
				}
			}
		}
		foreach($paths as $path) {
			$filemtime = @filemtime($path);
			if ($filemtime !== false) {
				$filemtimes[] = $filemtime;
			} else {
				Yii::log('Can\'t access ' . $path, CLogger::LEVEL_WARNING, 'ext.minScript.components.ExtMinScript');
			}
		}
		if(!empty($groups)) {
			$params['g'] = implode(',', $groups);
		}
		if(!empty($files)) {
			$params['f'] = urlencode(implode(',', $files));
		}
		if($this -> scriptDebug === true) {
			$params['debug'] = 1;
		}
		if (!empty($filemtimes)) {
			$params['lm'] = max($filemtimes);
		}
		$minScriptUrl = Yii::app() -> createUrl($this -> _controllerID . '/serve', $params);
		return $minScriptUrl;
	}

	/**
	 * Compare given string with minify's groupsConfig.
	 * @param string $str String to compare.
	 * @return boolean True if given string differs.
	 */
	protected function _compareGroupsConfig($str) {
		$groupsConfig = @file_get_contents($this -> _minifyDir . '/groupsConfig.php');
		if ($groupsConfig === false) {
			return false;
		}
		$str = str_replace('&lt;', '<', $str);
		if ($str != $groupsConfig) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Write string to minify's groupsConfig.
	 * @param string $str String to write.
	 */
	protected function _writeGroupsConfig($str) {
		$str = str_replace('&lt;', '<', $str);
		file_put_contents($this -> _minifyDir . '/groupsConfig.php', $str, LOCK_EX);
	}

}
