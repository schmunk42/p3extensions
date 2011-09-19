<?php

class P3StringHelper {
	
	public static function cleanName($name, $maxLength = 0) {
		$name = preg_replace("/[^.A-Za-z0-9_-]/", "", $name);
		if ($maxLength > 0 && strlen($name) > $maxLength) {
			$name = substr($name, 0, $maxLength / 2 - 2) . ".." . substr($name, strlen($name) - $maxLength / 2 + 1);
		}
		return $name;
	}
	
	public static function generateUniqueFilename($path) {
		$pathinfo = pathinfo($path);
		return $pathinfo['filename'] . uniqid('-') . '.' . $pathinfo['extension'];
	}

}

?>
