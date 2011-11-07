<?php
$_baseDir = (dirname($_SERVER['SCRIPT_NAME']) != '/')?dirname($_SERVER['SCRIPT_NAME']):'';
return array(
	'params' => array(
		'ext.ckeditor.options' => array(
			'type' => 'fckeditor',
			'height' => 400,
			'filebrowserWindowWidth' => '990',
			'filebrowserWindowHeight' => '800',
			'resize_minWidth' => '150',
			/* Toolbar */
			'toolbar_Custom' => array(
				array('Templates', '-', 'Maximize', 'Source', 'ShowBlocks', '-', 'Undo', 'Redo', '-', 'PasteText', 'PasteFromWord'),
				array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'NumberedList', 'BulletedList','-','BidiLtr','BidiRtl'),
				array('Table', 'Blockquote'),
				'/',
				array('Image', 'Flash', '-', 'Link', 'Unlink'),
				array('Bold', 'Italic', 'Underline', '-', 'UnorderedList', 'OrderedList', '-', 'RemoveFormat'),
				array('CreateDiv','Format', 'Styles')),
			'toolbar' => "Custom",
			/* Settings */
			'startupOutlineBlocks' => true,
			'pasteFromWordRemoveStyle' => true,
			'pasteFromWordKeepsStructure' => true,
			'templates_replaceContent' => true,
			#'ignoreEmptyParagraph' => false,
			#'forcePasteAsPlainText' => true,
			'contentsCss' => $_baseDir.'/css/ckeditor/ckeditor.css',
			'bodyId' => 'ckeditor',
			'bodyClass' => 'ckeditor',
			/* Assets will be published with publishAsset() */
			
			'templates_files' => array($_baseDir.'/css/ckeditor/cktemplates.js'),
			'stylesCombo_stylesSet' => 'my_styles:'.$_baseDir.'/css/ckeditor/ckstyles.js',
			
			/* Standard-way to specify URLs - deprecated */
			/*'filebrowserBrowseUrl' => '/p3media/ckeditor',
			'filebrowserImageBrowseUrl' => '/p3media/ckeditor/image',
			'filebrowserFlashBrowseUrl' => '/p3media/ckeditor/flash',*/
			// 'filebrowserUploadUrl' => 'null', // can not use, pre-resizing of images

			/* URLs will be parsed with createUrl() */
			'filebrowserBrowseCreateUrl'		=> array('/p3media/ckeditor'),
			'filebrowserImageBrowseCreateUrl'	=> array('/p3media/ckeditor/image'),
			'filebrowserFlashBrowseCreateUrl'	=> array('/p3media/ckeditor/flash'),
			
			'filebrowserUploadCreateUrl' => array('/p3media/import/upload'), // TODO (tbd)
			
		),
	),
)

?>
