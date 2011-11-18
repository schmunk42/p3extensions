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
 * Commponent ...
 * 
 * Based upon http://www.yiiframework.com/doc/guide/1.1/en/database.migration#c2550 from Leric
 * 
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.components
 * @since 3.0.3
 */

class P3ReturnUrl extends CApplicationComponent {
	public function init() {
		parent::init();
		
		$urlManager = Yii::createComponent('ext.p3extensions.sets.language.P3LangUrlManager');
		
		$loginUrl = $urlManager->createUrl(Yii::app()->user->loginUrl[0], array('lang'=>'de_de'));
		$requestUrl = Yii::app()->request->url;
		
		if ($requestUrl!=$loginUrl) {
			Yii::app()->user->returnUrl = Yii::app()->request->url;
		}
	}
}

?>
