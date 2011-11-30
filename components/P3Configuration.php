<?php
/**
 * Class file.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2011 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Configuration helper, reads files or arrays, skips missing files without 
 * throwing errors if file is not found
 * 
 * Based upon http://www.yiiframework.com/doc/guide/1.1/en/database.migration#c2550 from Leric
 * 
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.components
 * @since 3.0.1
 */
class P3Configuration {
	
	public $scanDirectories;
	private $_config;

	public function  __construct($directories) {
		$this->scanDirectories = $directories;
	}

	private function scan(){
		foreach($this->scanDirectories AS $dir){
            if (is_array($dir)) {
    			$config = $dir;
            } elseif (is_file($dir)) {
    			$config = require($dir);
	        } else {
				Yii::log('Configuration file '.$dir.' not found');
				continue;
			}
	        $this->_config = CMap::mergeArray($this->_config, $config);
		}
	}
	
	function toArray() {
		$this->scan();
		return $this->_config;
	}
	
}

?>
