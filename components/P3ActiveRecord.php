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
 * ActiveRecord, basic settings for p2 core models
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package p2.models
 * @since 2.0
 */
class P3ActiveRecord extends CActiveRecord {

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            #'P2Info' => array(self::HAS_ONE, 'P2Info', 'modelId',
             #   'condition'=>'P2Info.model = "'.get_class($this).'"'
            #),
        );
    }

    /**
     * @return array model behaviours
     */
    public function behaviors() {
        return array(
            #'Logable'   =>'application.modules.p2.behaviors.P2ActiveRecordLogableBehavior',
            #'Info'      =>'application.modules.p2.behaviors.P2ActiveRecordInfoBehavior',
        );
    }

    /**
     * @return array scopes.
     */
    public function scopes() {
        $checkAccess = (Yii::app()->user->isGuest)?'':' OR P2Info.checkAccess IN ("'.implode('","',array_keys(Yii::app()->authManager->getRoles(Yii::app()->user->id))).'")';

        $scopes = array(
            'localized'=>array(
                #'with' => array('P2Info'),
                #'condition' => 'P2Info.language = "'.Yii::app()->language.'" or P2Info.language IS NULL',
            ),
            'localizedStrict'=>array(
                #'with' => array('P2Info'),
                #'condition' => 'P2Info.language = "'.Yii::app()->language.'" ',
            ),
            'active'=>array(
                #'with' => array('P2Info'),
                #'condition' => '(P2Info.status = '.P2Info::STATUS_ACTIVE.' OR P2Info.status = '.P2Info::STATUS_LOCKED.' OR P2Info.status = '.P2Info::STATUS_HIDDEN.')',
            ),
            'ongoing'=>array(
                #'with' => array('P2Info'),
                'condition' => '(P2Info.begin <= NOW() AND (P2Info.end > NOW() OR P2Info.end IS NULL OR P2Info.end = 0))',
            ),
            'checkAccess'=>array(
                #'with' => array('P2Info'),
                #'condition' => 'P2Info.checkAccess IS NULL '.$checkAccess,
            ),
        );
        $scopes['default'] = array(
            #'with' => array('P2Info'),
            /*'condition' => '('.
                $scopes['localized']['condition'].') AND ('.
                $scopes['active']['condition'].') AND ('.
                $scopes['checkAccess']['condition'].
                ')',*/
        );

        return $scopes;
    }


}
?>
