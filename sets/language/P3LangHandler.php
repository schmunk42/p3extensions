<?php
/**
 * Class File, based upon http://www.yiiframework.com/extension/langhandler/
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Application Component, managing I18N URLs
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 371 2010-02-04 01:51:13Z schmunk $
 * @package extensions.langhandler
 * @since 2.0
 */
class P3LangHandler extends CApplicationComponent {

    const DATA_KEY = 'lang';
    public $languages = array();

    public function init() {
        array_push($this->languages, Yii::app()->getLanguage());
        $this->parseLanguage();

        if(Yii::app()->getUrlManager()->showScriptName)
            Yii::app()->homeUrl = Yii::app()->getRequest()->getScriptUrl();
			else
				Yii::app()->homeUrl = Yii::app()->getRequest()->getBaseUrl().'/'.Yii::app()->language.'/';

#Yii::app()->setHomeUrl(Yii::app()->language.'/');
    }

    private function parseLanguage() {
        Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
        if(!isset($_GET[self::DATA_KEY])) {
            $defaultLang = Yii::app()->getRequest()->getPreferredLanguage();
            if (in_array($defaultLang, $this->languages)) {
                Yii::app()->setLanguage($defaultLang);
            } else {
                Yii::app()->setLanguage($this->languages[0]);
            }
        } elseif($_GET[self::DATA_KEY]!=Yii::app()->getLanguage() && in_array($_GET[self::DATA_KEY],$this->languages)) {
            Yii::app()->setLanguage($_GET[self::DATA_KEY]);
        }
    }
}
?>