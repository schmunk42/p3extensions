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
     * whether to use the preferred user language or the default application language
     * @var bool
     */
    public $usePreferred = true;

    /**
     * whether to set the application homeUrl to '/' prepended with the application language
     * @var bool
     */
    public $localizedHomeUrl = true;

    /**
     * Handles language detection and application setting by URL parm specified in DATA_KEY
     */
    public function init()
    {
        // parsing needed if urlFormat 'path'
        if (Yii::app() instanceof CWebApplication) {
            Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
        }

        // 1. get language preference
        $preferred = null;
        if (isset($_GET[self::DATA_KEY])) {
            // use language from URL
            $preferred = $_GET[self::DATA_KEY];
        } elseif ($this->usePreferred) {
            // use preferred browser language as default
            $preferred = Yii::app()->request->preferredLanguage;
        } else {
            $preferred = Yii::app()->language;
        }

        // 2. select language based on available languages and mappings
        $lang = $this->resolveLanguage($preferred);

        if (is_null($lang) && $this->matchShort === true) {
            $preferredShort = substr($preferred, 0, 2);
            $lang           = $this->resolveLanguage($preferredShort);
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

        if ($this->localizedHomeUrl) {
            $controller         = new CController('P3LangHandlerDummy');
            Yii::app()->homeUrl = $controller->createUrl('/');
        }
    }

    /**
     * Returns the language part for the application language
     * Eg. `de` for `Yii::app()->langauge = 'de_ch`
     * @return string
     */
    public function getLanguage()
    {
        return strstr(Yii::app()->language, '_', true);
    }

    /**
     * Returns the region part for application language, `null` if the short form is used.
     * Eg. `ch` for `Yii::app()->langauge = 'de_ch`
     * @return string
     */
    public function getCountry()
    {
        if (strstr(Yii::app()->language, '_')) {
            return substr(strstr(Yii::app()->language, '_'), 1);
        } else {
            return null;
        }
    }


    /**
     * Resolves an available language from a preferred language.
     *
     * @param type $preferred
     *
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