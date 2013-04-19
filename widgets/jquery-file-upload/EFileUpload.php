<?php

class EFileUpload extends CWidget {

	public $view = "fileUpload";
	public $initJs = null;

	public function init(){
		$this->registerClientScripts();
	}

	public function run(){
		$this->render($this->view);
	}

	private function registerClientScripts(){
		$assetsPath = Yii::getPathOfAlias('jquery-file-upload');

		$cs = Yii::app()->clientScript;
		$am = Yii::app()->assetManager;

		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('jquery.ui');

		$cs->registerScriptFile($am->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery.tmpl.min.js'), CClientScript::POS_END);
		Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css'); // TODO: use default theme or override css in config main?
		$cs->registerScriptFile($am->publish($assetsPath.DIRECTORY_SEPARATOR.'jquery.fileupload.js'), CClientScript::POS_END);
		$cs->registerScriptFile($am->publish($assetsPath.DIRECTORY_SEPARATOR.'jquery.fileupload-ui.js'), CClientScript::POS_END);
		$cs->registerScriptFile($am->publish($assetsPath.DIRECTORY_SEPARATOR.'jquery.iframe-transport.js'), CClientScript::POS_END);
		$cs->registerCssFile($am->publish($assetsPath.DIRECTORY_SEPARATOR.'jquery.fileupload-ui.css'));

		if ($this->initJs !== null) {
			$cs->registerScriptFile($this->initJs, CClientScript::POS_END);
		} else {
			$cs->registerScriptFile($am->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'init.js'), CClientScript::POS_END);
		}

	}
}
?>
