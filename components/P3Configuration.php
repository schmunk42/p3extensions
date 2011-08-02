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
            } else {
    			$config = require($dir);
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
