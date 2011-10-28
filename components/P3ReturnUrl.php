<?php

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
