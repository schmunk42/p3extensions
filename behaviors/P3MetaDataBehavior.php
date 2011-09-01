<?php

class P3MetaDataBehavior extends CActiveRecordBehavior {
	
	public $metaDataRelation;
	
	private function getMetaDataRelation(){
		if (!$this->metaDataRelation) {
			// auto-find
			$class = get_class($this->owner);
			$metaDataRelation = strtolower($class[0]).substr($class,1).'Meta';
			return $this->owner->$metaDataRelation;
		} elseif ($this->metaDataRelation == "_self_") {
			// special case for meta data tables
			return $this->owner;
		} else {
			// manual setting
			return $this->owner->{$this->metaDataRelation};
		}
	}
	
	public function beforeDelete($event) {
		parent::beforeDelete($event);
		if ($this->getMetaDataRelation()->checkAccessDelete) {
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
		if ($this->getMetaDataRelation()->checkAccessUpdate) {
			if (Yii::app()->user->checkAccess($this->getMetaDataRelation()->checkAccessUpdate) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			}
		}
		return true;
	}	

}

?>