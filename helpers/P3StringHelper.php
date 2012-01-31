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
 * DEPRECATED
 * 
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.helpers
 * @since 3.0.1
 */
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
