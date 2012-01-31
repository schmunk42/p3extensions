<?php

/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Behavior, adds logging to active records
 *
 * Note: Porting in progress ...
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.behaviors
 * @since 3.0.3
 */
class P3LoggableBehavior extends CActiveRecordBehavior {

	private $_oldattributes = array();

	public function afterSave($event) {
		if (!$this->Owner->isNewRecord) {

			// new attributes
			$newattributes = $this->Owner->getAttributes();
			$oldattributes = $this->getOldAttributes();

			// compare old and new
			$hasChanges = false;
			$fields = "";
			foreach ($newattributes as $name => $value) {
				if (!empty($oldattributes)) {
					$old = $oldattributes[$name];
				} else {
					$old = '';
				}
				if ($value != $old) {
					$hasChanges = true;
					$fields .= $name . ",";
				}
			}
			if ($hasChanges) {
				//$changes = $name . ' ('.$old.') => ('.$value.'), ';

				$log = new P2Log;
				$log->description = 'User ' . Yii::app()->user->Name
					. ' changed ' . $fields . ' for '
					. get_class($this->Owner)
					. ' #' . $this->Owner->getPrimaryKey() . '.';
				$log->data = CJSON::encode($this->getOldAttributes());

				$log->action = 'CHANGE';
				$log->model = get_class($this->Owner);
				$log->modelId = $this->Owner->getPrimaryKey();
				$log->changes = $fields;
				$log->createdAt = new CDbExpression('NOW()');
				$log->createdBy = Yii::app()->user->id;
				$log->save();
			}
		} else {
			$log = new P2Log;
			$log->description = 'User ' . Yii::app()->user->Name
				. ' created ' . get_class($this->Owner)
				. ' #' . $this->Owner->getPrimaryKey() . '.';
			$log->action = 'CREATE';
			$log->model = get_class($this->Owner);
			$log->modelId = $this->Owner->getPrimaryKey();
			$log->changes = '';
			$log->createdAt = new CDbExpression('NOW()');
			$log->createdBy = Yii::app()->user->id;
			$log->save();
		}
	}

	public function afterDelete($event) {
		$log = new P2Log;
		$log->description = 'User ' . Yii::app()->user->Name . ' deleted '
			. get_class($this->Owner)
			. ' #' . $this->Owner->getPrimaryKey() . '.';
		$log->data = CJSON::encode($this->getOldAttributes());

		$log->action = 'DELETE';
		$log->model = get_class($this->Owner);
		$log->modelId = $this->Owner->getPrimaryKey();
		$log->changes = '';
		$log->createdAt = new CDbExpression('NOW()');
		$log->createdBy = Yii::app()->user->id;
		$log->save();
	}

	public function afterFind($event) {
		// Save old values
		$this->setOldAttributes($this->Owner->getAttributes());
	}

	public function getOldAttributes() {
		return $this->_oldattributes;
	}

	public function setOldAttributes($value) {
		$this->_oldattributes = $value;
	}

}

?>
