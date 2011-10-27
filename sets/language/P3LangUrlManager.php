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
 * Application component
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2BlogWidget.php 371 2010-02-04 01:51:13Z schmunk $
 * @package extensions.langhandler
 * @since 2.0
 */
class P3LangUrlManager extends CUrlManager {
    
    public function createUrl($route,$params=array(),$ampersand='&') {

        if (isset($params['lang']) && $params['lang'] == "__EMPTY__") {
            unset($params['lang']);
        } elseif (!isset($params['lang'])) {
            $params['lang']=Yii::app()->GetLanguage();
        }
        return parent::createUrl($route, $params, $ampersand);
    }
    
}
?>