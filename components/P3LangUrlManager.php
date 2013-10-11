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
 * Url manager, creates URLs with 'lang' param
 * Based upon http://www.yiiframework.com/extension/langhandler/
 *
 * @see P3LangHandler
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.components
 * @since 0.5
 */
class P3LangUrlManager extends CUrlManager {

    public function createUrl($route,$params=array(),$ampersand='&') {

        if (isset($params['lang']) && $params['lang'] == "__EMPTY__") {
            unset($params['lang']);
        } elseif (!isset($params['lang'])) {
            $params['lang'] = Yii::app()->GetLanguage();
        }
        return parent::createUrl($route, $params, $ampersand);
    }
    
}
?>