<?php
/**
 * Class file.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2012 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * CArrayValidator validates that the attribute value is of certain length.
 *
 * Note, this validator should only be used with Array-typed attributes.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.commands
 * @since 3.0.3
 */
class P3ArrayValidator extends CValidator {
    /**
     * @var integer maximum length. Defaults to null, meaning no maximum limit.
     */
    public $max;
    /**
     * @var integer minimum length. Defaults to null, meaning no minimum limit.
     */
    public $min;
    /**
     * @var integer exact length. Defaults to null, meaning no exact length limit.
     */
    public $is;
    /**
     * @var Array user-defined error message used when the value is too long.
     */
    public $tooShort;
    /**
     * @var Array user-defined error message used when the value is too short.
     */
    public $tooLong;
    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty=true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel the object being validated
     * @param Array the attribute being validated
     */
    protected function validateAttribute($object,$attribute) {
        $value=$object->$attribute;
        if($this->allowEmpty && $this->isEmpty($value))
            return;
        elseif(!is_array($value)) {
            $message=$this->message!==null?$this->message:Yii::t('P2Module.p2','{attribute} is not valid.');
            $this->addError($object,$attribute,$message);
        }

        $length=count($value);
        if($this->min!==null && $length<$this->min) {
            $message=$this->tooShort!==null?$this->tooShort:Yii::t('P2Module.p2','{attribute} is too short (minimum is {min} entries).');
            $this->addError($object,$attribute,$message,array('{min}'=>$this->min));
        }
        if($this->max!==null && $length>$this->max) {
            $message=$this->tooLong!==null?$this->tooLong:Yii::t('P2Module.p2','{attribute} is too long (maximum is {max} entries).');
            $this->addError($object,$attribute,$message,array('{max}'=>$this->max));
        }
        if($this->is!==null && $length!==$this->is) {
            $message=$this->message!==null?$this->message:Yii::t('P2Module.p2','{attribute} is of the wrong length (should be {length} entries).');
            $this->addError($object,$attribute,$message,array('{length}'=>$this->is));
        }
    }
}

