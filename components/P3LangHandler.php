<?php

/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Handles language detection and application setting by URL parm specified in 
 * DATA_KEY. Uses first language as a fallback language.
 * 
 * Based upon http://www.yiiframework.com/extension/langhandler/
 * 
 * @see P3LangUrlManager
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.components
 * @since 3.0.3
 */
class P3LangHandler extends CApplicationComponent {
	/**
	 * $_GET param used for language detection
	 */
	const DATA_KEY = 'lang';
	/**
	 * Available languages
	 * @var type 
	 */
	public $languages = array();

	/**
	 * Handles language detection and application setting by URL parm specified in DATA_KEY
	 */
	public function init() {
		if (!isset($_GET[self::DATA_KEY])) {
			$preferred = Yii::app()->getRequest()->getPreferredLanguage();
			if (in_array($preferred, $this->languages)) {
				Yii::app()->setLanguage($preferred);
			} else {
				Yii::app()->setLanguage($this->languages[0]);
			}
		} elseif ($_GET[self::DATA_KEY] != Yii::app()->getLanguage() && in_array($_GET[self::DATA_KEY], $this->languages)) {
			Yii::app()->setLanguage($_GET[self::DATA_KEY]);
		}

#		array_push($this->languages, Yii::app()->getLanguage());

#		$this->parseLanguage();

		#if (Yii::app()->getUrlManager()->showScriptName)
		#	Yii::app()->homeUrl = Yii::app()->getRequest()->getScriptUrl();
		#else
		#	Yii::app()->homeUrl = $this->Yii::app()->getRequest()->getBaseUrl() . '/' . Yii::app()->language . '/';
	}

	/**
	 * Determines langauge
	 */
	private function parseLanguage() {
		Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
	}

}

?>