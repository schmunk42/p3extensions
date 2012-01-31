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
 * Widget for changing application language
 * 
 * @see P3LangHandler
 * @see P3LangUrlManager
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3extensions.widgets
 * @since 3.0.3
 */
class P3LanguageDropDown extends CWidget {

    /**
	 * Key-value pairs of language code and language display name
	 * @var type array
	 */
	public $languages;
	/**
	 * Wheter to enable the widget on error actions, defaults to: false
	 * @var type 
	 */
    public $enabledOnError = false;
    
    function run() {

        $name = "lang";        
        $select = Yii::app()->language;
        $data = $this->languages;        
        
		if(!isset($data[$select])) {
			$data[$select] = "*".Yii::app()->language;
		}
        
        $htmlOptions = array('id' => uniqid(get_class()), 'submit'=>'');

        if($this->controller->action->id == "error" && !$this->enabledOnError)
            $htmlOptions['disabled'] = true;
		
		// request URL without lang param
        $params = CMap::mergeArray($_GET,array('lang'=>'__EMPTY__'));
        
        $code = CHtml::beginForm($this->controller->createUrl($this->controller->id."/".$this->controller->action->id, $params), 'get');
        $code .= CHtml::dropDownList($name, $select, $data, $htmlOptions);
        $code .= CHtml::endForm();

        echo $code;
    }
}
?>
