<?php

class P3TranslationBehavior extends CActiveRecordBehavior {

	/**
	 * Name of the relation identifier in the model which should contain
	 * extended translation attributes
	 * @var string
	 */
	public $relation;
	public $fallbackLanguage;

	public function t($name, $language = null, $fallback = false) {
		if ($language === null) {
			$language = Yii::app()->language;
		}

		if ($this->hasTranslationAttribute($name)) {
			return $this->resolveTranslation($language, $name, $fallback);
		} else {
			throw new CException("Translation property '{$name}' is not defined. ");
		}
	}

	private function resolveTranslation($language, $attr, $fallback) {
		// parse models into array
		foreach ($this->owner->getRelated($this->relation) AS $translationModel) {
			$models[$translationModel->language] = $translationModel;
		}

		if (isset($models[$language])) {
			// desired model
			return $models[$language]->$attr;
		} else if (isset($models[$this->fallbackLanguage])) {
			// fallback model
			return $models[$this->fallbackLanguage]->$attr."*";
		} else if ($fallback === true) {		
			// return string if there's no value, but fallback in on
			return "not yet translated**";
		} else {
			return null;
		}
	}

	private function hasTranslationAttribute($attr) {
		$relations = $this->owner->relations();
		$model = new $relations[$this->relation][1];
		return $model->hasAttribute($attr);
	}

}

?>