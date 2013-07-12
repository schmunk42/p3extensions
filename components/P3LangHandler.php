<?php

/**
 * Class File
 * @author    Tobias Munk <schmunk@usrbin.de>
 * @link      http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license   http://www.phundament.com/license/
 */

/**
 * Handles language detection and application setting by URL parm specified in
 * DATA_KEY. Uses first language as a fallback language.
 * Based upon http://www.yiiframework.com/extension/langhandler/
 * @see     P3LangUrlManager
 * @author  Tobias Munk <schmunk@usrbin.de>
 * @author  Fredrik Wollsén <fredrik@neam.se>
 * @package p3extensions.components
 */
class P3LangHandler extends CApplicationComponent
{
    /**
     * $_GET param used for language detection
     */
    const DATA_KEY = 'lang';
    /**
     * Available languages
     * @var array
     */
    public $languages = array();
    /**
     * Mappings of languages to fallback to
     * @var array
     */
    public $mappings = array();
    /**
     * if long language specifiers, like `de-de` and `de-ch` should be translated to 'de'
     * @var boolean
     */
    public $matchShort = true;
    /**
     * fallback to the default application language, if specified lanugae is not configured
     * @var boolean
     */
    public $fallback = true;

    /**
     * Handles language detection and application setting by URL parm specified in DATA_KEY
     */
    public function init()
    {
        // parsing needed if urlFormat 'path'
        Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());

		// 1. get language preference
		$preferred = null;
		if (isset($_GET[self::DATA_KEY])) {
			// use language from URL
			$preferred = $_GET[self::DATA_KEY];
		} else {
			// use preferred browser language as default
			$preferred = Yii::app()->getRequest()->getPreferredLanguage();
		}

		// 2. select language based on available languages and mappings
		$lang = $this->resolveLanguage($preferred);

		if (is_null($lang) && $this->matchShort === true) {
			$preferredShort = substr($preferred, 0, 2);
			$lang = $this->resolveLanguage($preferredShort);
		}

		// 3. set the language
		if (in_array($lang, $this->languages)) {
			Yii::app()->setLanguage($lang);
		} else {
			// fallback or output error
			if ($this->fallback) {
				// default app language
			} else {
				throw new CHttpException(404, "Language '{$_GET[self::DATA_KEY]}' is not available.");
			}
		}
	}

	/**
	 * Resolves an available language from a preferred language.
	 *
	 * @param type $preferred
	 * @return Resolved language if configured app language or available through fallback mapping
	 */
	private function resolveLanguage($preferred)
	{
		if (in_array($preferred, $this->languages)) {
			return $preferred;
		} elseif (isset($this->mappings[$preferred])) {
			return $this->mappings[$preferred];
		}
		return null;
	}

}

?>