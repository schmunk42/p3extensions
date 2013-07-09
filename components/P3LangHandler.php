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


        // use preferred browser language
        if (!isset($_GET[self::DATA_KEY])) {
            $preferred      = Yii::app()->getRequest()->getPreferredLanguage();
            $preferredShort = substr($preferred, 0, 2);

            if (in_array($preferred, $this->languages)) {
                Yii::app()->setLanguage($preferred);
            } elseif (($this->matchShort === true) && in_array($preferredShort, $this->languages)) {
                Yii::app()->setLanguage($preferredShort);
            } else {
                Yii::app()->setLanguage(Yii::app()->language);
            }
        }
        // use language form URL
        elseif ($_GET[self::DATA_KEY] != Yii::app()->getLanguage() && in_array(
                $_GET[self::DATA_KEY],
                $this->languages
            )
        ) {
            Yii::app()->setLanguage($_GET[self::DATA_KEY]);
        }
        // fallback or output error
        else {
            if (Yii::app()->language != $_GET[self::DATA_KEY] && $this->fallback === false) {
                throw new CHttpException(404, "Language '{$_GET[self::DATA_KEY]}' is not available.");
            } else {
                // default app language
            }
        }
    }

}

?>