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
 * Behavior, encrypts given attributes with CSecurityManager
 * 
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.behaviors
 * @since 3.0.3
 */
class P3CryptBehavior extends CActiveRecordBehavior {

    /**
	 * Attributes which should be crypted
	 * 
	 * @var type array
	 */
	public $attributes = array();

    public function beforeSave($event) {

        foreach($this->Owner->attributes AS $key => $value) {
            if(in_array($key, $this->attributes) && $value) {
                // utf8 encode / decode is needed
                $this->Owner->$key = utf8_encode(Yii::app()->securityManager->encrypt($value));
                #exit;
            }

        }
    }

    public function afterFind($event) {
        foreach($this->Owner->attributes AS $key => $value) {
            if(in_array($key, $this->attributes) && $value)
                // utf8 encode / decode is needed
                $this->Owner->$key = Yii::app()->securityManager->decrypt(utf8_decode($value));
        }
    }

}
?>
