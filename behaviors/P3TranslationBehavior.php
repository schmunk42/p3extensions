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
 * Provides a translation for attributes in the specified relation
 * 
 * <pre>
 * 'Translation' => array(
 *   'class' => 'ext.p3extensions.behaviors.P3TranslationBehavior',
 *   'relation' => 'daContentTranslations',
 *   'fallbackLanguage' => 'de_de'
 * )
 * </pre>
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.behaviors
 * @since 3.0.3
 */
class P3TranslationBehavior extends CActiveRecordBehavior {

	/**
	 * Name of the relation identifier in the model which should contain
	 * extended translation attributes
	 * @var string
	 */
	public $relation;

	/**
	 * Language to use if preferred language is not found
	 * @var type 
	 */
	public $fallbackLanguage;

	/**
	 * Value to use if preferred value from language is not found
	 * @var type 
	 */
	public $fallbackValue = "not yet translated**";

	/**
	 * Attributes which should not be translated
	 * @var type 
	 */
	public $attributesBlacklist = array();

	#public $attributesWhitelist = array();

	/**
	 * Tranlates attribute
	 * @param type $name attribute to translate
	 * @param type $language preferred language
	 * @param type $fallback fallback language
	 * @return mixed
	 */
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

	public function getTranslationModel($language = null) {
		if ($language === null) {
			$language = Yii::app()->language;
		}
		// parse models into array
		$models = array();
		foreach ($this->owner->getRelated($this->relation) AS $translationModel) {
			$models[$translationModel->language] = $translationModel;
		}

		if (isset($models[$language])) {
			// desired model
			return $models[$language];
		} else {
			return null;
		}
	}

	private function resolveTranslation($language, $attr, $fallback) {
		// parse models into array
		$models = array();
		foreach ($this->owner->getRelated($this->relation) AS $translationModel) {
			$models[$translationModel->language] = $translationModel;
		}

		if (isset($models[$language])) {
			// desired model
			return $models[$language]->$attr;
		} else if ($fallback === true && isset($models[$this->fallbackLanguage])) {
			// fallback model
			return $models[$this->fallbackLanguage]->$attr; # . "*";
		} else if ($fallback === true && !in_array($attr, $this->attributesBlacklist)) {
			// return string if there's no value, but fallback in on
			return $this->fallbackValue;
		} else {
			return null;
		}
	}

	private function hasTranslationAttribute($attr) {
		$relations = $this->owner->relations();
		$model = new $relations[$this->relation][1];

		// check relations
		$modelRelations = $model->relations();
		if (isset($modelRelations[$attr])) {
			return true;
		}

		return ($model->hasAttribute($attr));
	}

}

?>