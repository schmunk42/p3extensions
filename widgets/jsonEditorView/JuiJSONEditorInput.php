<?php
/**
 * JuiJSONEditorInput class file.
 *
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @link http://www.cleverit.com.pl
 * @copyright Copyright &copy; 2008-2011 CleverIT - Dariusz Górecki
 * @license http://www.gnu.org/licenses/lgpl-3.0.html GNU LGPL 3.0
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * JuiJSONEditorInput displays WYSWIG editor for JSON data.
 * In terms of raw input field or input field for CModel instance
 *
 * Encapsulates the JSON Editor plugin for jQuery
 * ({@link http://plugins.jquery.com/project/jsoneditor})
 *
 * To use set {@link data} to the JSON string and {@link name} to field name,
 * or set {@link model} for CModel instance, and {@link attribute} to model attribute
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @version 1.0
 */
class JuiJSONEditorInput extends CJuiInputWidget
{
	/**
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;

	/**
	 * @var string JSON contents for initial display, default empty object '{}'
	 */
	public $data = '{}';

	/**
	 * @var string name of the 'root' node in editor
	 */
	public $rootNodeName = 'root';

	/**
	 * @var string label for input button, default: 'Switch to text input'
	 */
	public $inputButtonLabel = 'Switch to text input';

	/**
	 * @var string label for init button, default: 'Switch to JSON editor'
	 */
	public $initButtonLabel = 'Switch to JSON editor';

	public function init()
	{
		list($name, $id) = $this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if(!isset($this->htmlOptions['style']))
			$this->htmlOptions['style'] = 'border:solid 2px #ccc; width:100%;';

		if($this->hasModel() && is_string($this->model->{$this->attribute}))
		{
			$this->data = $this->model->{$this->attribute};
		}

        /* TODO - hotfix: if value is not JSON, auto-convert it */
        if (substr($this->data,0,1) !== "{") {
            $this->data = '{"CONVERTED_FROM_STRING":"'.$this->data.'"}';
        }

		$this->registerClientScript();
	}

	public function run()
	{
		echo CHtml::button($this->inputButtonLabel, array(
			'onclick'=>"jQuery(\"#{$this->htmlOptions['id']}\").jsoneditor('input')"
		));
		echo CHtml::button($this->initButtonLabel, array(
			'onclick'=>"jQuery(\"#{$this->htmlOptions['id']}\").jsoneditor('init');"
		));
		echo CHtml::tag('div', $this->htmlOptions, "");
	}

	public function registerClientScript()
	{
		$cs=Yii::app()->getClientScript();
		$url = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'lib');

		$name = $this->htmlOptions['name'];
		$id = $this->htmlOptions['id'];

		$cs->registerScriptFile($url.'/jquery.json-2.2.min.js');
		$cs->registerScriptFile($url.'/jquery.jsoneditor.js');

		if($this->cssFile===null)
			$cs->registerCssFile($url.'/jsoneditor.css');
		else if($this->cssFile!==false)
			$cs->registerCssFile($this->cssFile);

		$cs->registerScript(
			'JuiJSONEditorInput#'.$id,
			"jQuery(\"#{$id}\").jsoneditor('init', {root:'{$this->rootNodeName}', data:{$this->data}});"
		);

		$cs->registerScript(
			'JuiJSONEditorInput-form#'.$id,
			"
				jQuery(\"#{$id}\").parents('form:last').submit(function(){
					var obj = jQuery(\"#{$id}\");

					obj.jsoneditor('input');
					obj.children('textarea').attr('name', '{$name}');
				});
			"
		);
	}
}
