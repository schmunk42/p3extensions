<?php

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
