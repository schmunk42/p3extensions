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
 * Set the current page as the return url if it gets initialized
 *
 * Commponent must be initialized (called) in a controller.
 * <pre>
 * Yii::app()->returnUrl;
 * </pre>
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.components
 * @since 3.0.3
 */
class P3ReturnUrl extends CApplicationComponent
{

    public function init()
    {
        parent::init();

        // TODO
        $urlManager = Yii::createComponent('P3LangUrlManager');

        $loginUrl = $urlManager->createUrl(Yii::app()->user->loginUrl[0]);
        $requestUrl = Yii::app()->request->url;

        if ($requestUrl != $loginUrl) {
            Yii::app()->user->returnUrl = Yii::app()->request->url;
        }
    }

}

?>
