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
 * Meta Data behavior
 * 
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.behaviors
 * @since 3.0.1
 */

class P3MetaDataBehavior extends CActiveRecordBehavior {

	/**
	 * Name of the meta data relation identifier in the 'parent' model
	 * @var string
	 */
	public $metaDataRelation;
	public $contentRelation;
	public $parentRelation;
	public $childrenRelation;

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
		if ($this->resolveMetaDataModel() !== null) {
			if ($this->resolveMetaDataModel()->checkAccessDelete && Yii::app()->user->checkAccess($this->resolveMetaDataModel()->checkAccessDelete) === false) {
				throw new CHttpException(403, "You are not authorized to perform this action. Access restricted by P3MetaDataBehavior.");
				return false;
			} else {
				if ($this->metaDataRelation !== "_self_")
					$this->resolveMetaDataModel()->delete();
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
			$primaryRole = key(Yii::app()->authManager->getRoles(Yii::app()->user->id));
			$metaModel->checkAccessUpdate = $primaryRole;
			$metaModel->checkAccessDelete = $primaryRole;
			$metaModel->createdAt = date('Y-m-d H:i:s');
			$metaModel->createdBy = Yii::app()->user->id;
			$metaModel->model = get_class($this->owner);
		} else {
			$metaModel = $this->resolveMetaDataModel();
			$metaModel->modifiedAt = date('Y-m-d H:i:s');
			$metaModel->modifiedBy = Yii::app()->user->id;
		}
		$metaModel->save();
		return true;
	}

	public function getChildren() {
		$return = array();
		$children = $this->resolveMetaDataModel()->{$this->childrenRelation};
		if ($children !== array()) {
			foreach($children AS $metaModel) {
				$return[] = $metaModel->{$this->contentRelation};
			}
		}
		return $return;
	}

	public function getParent() {
		return $this->resolveMetaDataModel()->{$this->parentRelation}->{$this->contentRelation};
	}

}

?>