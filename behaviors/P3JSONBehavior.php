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
 * Behavior, converts arrays into JSON strings
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ActiveRecordJSONBehavior.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.behaviors
 * @since 2.0
 */
class P3JSONBehavior extends CActiveRecordBehavior {

    public function beforeSave($event) {
        foreach($this->Owner->attributes AS $key => $value) {
            if(is_array($this->Owner->attributes[$key]))
                $this->Owner->$key = CJSON::encode($value);
        }
    }

    public function afterFind($event) {
        foreach($this->Owner->attributes AS $key => $value) {
            if (CJSON::decode($value))
                $this->Owner->$key = CJSON::decode($value);
        }
    }

}
?>
