<?php

class P3MetaDataBehavior extends CActiveRecordBehavior {

	/**
	 * Name of the meta data relation identifier in the 'parent' model
	 * @var string
	 */
	public $metaDataRelation;

	const STATUS_DELETED = 0;
	const STATUS_DRAFT = 10;
	const STATUS_PENDING = 20;
	const STATUS_ACTIVE = 30;
	const STATUS_LOCKED = 40;
	const STATUS_HIDDEN = 50;
	const STATUS_ARCHIVE = 60;

	private function resolveMetaDataModel() {
		if (!$this->metaDataRelation) {
			throw new CException("Attribute 'metaDataRelation' for model '" . get_class($this->owner) . "' not set.");
		} elseif ($this->metaDataRelation == "_self_") {
			// special case for meta data tables
			return $this->owner;
		} elseif (strpos($this->metaDataRelation, ".")) {
			// if there's a dot in the name, build the return value in object notation
			$parts = explode(".", $this->metaDataRelation);
			$return = $this->owner;
			foreach ($parts AS $part) {
				$return = $return->$part;
			}
			return $return;
		} else {
			// manual setting
			return $this->owner->{$this->metaDataRelation};
		}
	}

	public function beforeDelete($event) {
		parent::beforeDelete($event);
		if ($this->resolveMetaDataModel() !== null && $this->resolveMetaDataModel()->checkAccessDelete) {
			if (Yii::app()->user->checkAccess($this->resolveMetaDataModel()->checkAccessDelete) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			} else {
				
			}
		}
		return true;
	}

	public function beforeSave($event) {
		parent::beforeSave($event);
		if ($this->resolveMetaDataModel() !== null && $this->resolveMetaDataModel()->checkAccessUpdate) {
			if (Yii::app()->user->checkAccess($this->resolveMetaDataModel()->checkAccessUpdate) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			}
		}
		return true;
	}

	public function afterSave($event) {
		parent::afterSave($event);

		// do not auto-create meta data information for meta data table itself (recursion).
		if ($this->metaDataRelation == '_self_') {
			return true;
		}

		// create new meta data record or just update modifiedBy/At columns
		if ($this->resolveMetaDataModel() === null) {
			$metaClassName = $this->owner->getActiveRelation($this->metaDataRelation)->className;
			$metaModel = new $metaClassName;
			$metaModel->id = $this->owner->id;
			$metaModel->status = self::STATUS_ACTIVE;
			$metaModel->language = Yii::app()->language;
			$metaModel->owner = Yii::app()->user->id;
			$metaModel->createdAt = new CDbExpression('NOW()');
			$metaModel->createdBy = Yii::app()->user->id;
			$metaModel->model = get_class($this->owner);
		} else {
			$metaModel = $this->resolveMetaDataModel();
			$metaModel->modifiedAt = new CDbExpression('NOW()');
			$metaModel->modifiedBy = Yii::app()->user->id;
		}
		$metaModel->save();
		return true;
	}

}

?>