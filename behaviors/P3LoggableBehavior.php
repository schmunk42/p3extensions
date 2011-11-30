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
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ActiveRecordLogableBehavior.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.behaviors
 * @since 2.0
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
                    $fields .= $name.",";
                }
            }
            if ($hasChanges) {
                //$changes = $name . ' ('.$old.') => ('.$value.'), ';

                $log=new P2Log;
                $log->description=  'User ' . Yii::app()->user->Name
                    . ' changed ' . $fields . ' for '
                    . get_class($this->Owner)
                    . ' #' . $this->Owner->getPrimaryKey() .'.';
                $log->data = CJSON::encode($this->getOldAttributes());

                $log->action= 'CHANGE';
                $log->model=  get_class($this->Owner);
                $log->modelId=$this->Owner->getPrimaryKey();
                $log->changes= $fields;
                $log->createdAt= new CDbExpression('NOW()');
                $log->createdBy= Yii::app()->user->id;
                $log->save();
            }
        } else {
            $log=new P2Log;
            $log->description=  'User ' . Yii::app()->user->Name
                . ' created ' . get_class($this->Owner)
                . ' #' . $this->Owner->getPrimaryKey() .'.';
            $log->action=       'CREATE';
            $log->model=        get_class($this->Owner);
            $log->modelId=      $this->Owner->getPrimaryKey();
            $log->changes=        '';
            $log->createdAt= new CDbExpression('NOW()');
            $log->createdBy=       Yii::app()->user->id;
            $log->save();
        }
    }

    public function afterDelete($event) {
        $log=new P2Log;
        $log->description=  'User ' . Yii::app()->user->Name . ' deleted '
            . get_class($this->Owner)
            . ' #' . $this->Owner->getPrimaryKey() .'.';
        $log->data = CJSON::encode($this->getOldAttributes());

        $log->action=       'DELETE';
        $log->model=        get_class($this->Owner);
        $log->modelId=      $this->Owner->getPrimaryKey();
        $log->changes=        '';
        $log->createdAt = new CDbExpression('NOW()');
        $log->createdBy=       Yii::app()->user->id;
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
        $this->_oldattributes=$value;
    }
}
?>
