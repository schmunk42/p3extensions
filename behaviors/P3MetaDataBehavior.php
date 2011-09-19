<?php

class P3MetaDataBehavior extends CActiveRecordBehavior {

	public $metaDataRelation;

	const STATUS_ACTIVE = 30;

	private function getMetaDataRelation() {
		if (!$this->metaDataRelation) {
			// auto-find
			$class = get_class($this->owner);
			$metaDataRelation = strtolower($class[0]) . substr($class, 1) . 'Meta';
			return $this->owner->$metaDataRelation;
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
		if ($this->getMetaDataRelation() !== null && $this->getMetaDataRelation()->checkAccessDelete) {
			if (Yii::app()->user->checkAccess($this->getMetaDataRelation()->checkAccessDelete) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			} else {
				
			}
		}
		return true;
	}

	public function beforeSave($event) {
		parent::beforeSave($event);
		if ($this->getMetaDataRelation() !== null && $this->getMetaDataRelation()->checkAccessUpdate) {
			if (Yii::app()->user->checkAccess($this->getMetaDataRelation()->checkAccessUpdate) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			}
		}
		return true;
	}

	public function afterSave($event) {
		if ($this->getMetaDataRelation() === null) {
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
			$metaModel = $this->getMetaDataRelation();
			$metaModel->modifiedAt = new CDbExpression('NOW()');
			$metaModel->modifiedBy = Yii::app()->user->id;			
		}
		$metaModel->save();
		return true;
	}

}

?>