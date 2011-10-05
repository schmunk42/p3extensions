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
 * Behavior, handles file uploads
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ActiveRecordFileUploadBehavior.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p3.behaviors
 * @since 3.0
 */
class P3FileUploadBehavior extends CActiveRecordBehavior {
	const TRASH_FOLDER = 'trash';

	public $uploadInstance;
	public $dataAlias;
	public $trashAlias;
	public $dataSubdirectory;
	
	private $_baseDataPath;
	private $_fullDataPath;
	private $_relativeDataPath;
	private $_trashPath;

	public function afterValidate($event) {

		$this->prepareDataDirectory();
		$file = CUploadedFile::getInstanceByName($this->uploadInstance);

		if ($file instanceof CUploadedFile && $file->getError() == UPLOAD_ERR_OK && !$this->Owner->hasErrors()) {

			$uniqueFilename = P3StringHelper::generateUniqueFilename($file->getName());
			$fullFilePath = $this->_fullDataPath . DIRECTORY_SEPARATOR . $uniqueFilename;
			$relativeFilePath = $this->_relativeDataPath . DIRECTORY_SEPARATOR . $uniqueFilename;

			if ($file->saveAs($fullFilePath)) {
				#echo $fullFilePath;exit;
				if (!$this->Owner->isNewRecord) {
					$this->deleteFile($this->Owner->path);
				}
				if (!$this->Owner->title) {
					$this->Owner->title = P3StringHelper::cleanName($file->name,32);					
				}
				$this->Owner->path = $relativeFilePath;
				$this->Owner->mimeType = $file->type;
				$this->Owner->size = $file->size;
				$this->Owner->originalName = $file->name;
				$this->Owner->md5 = md5_file($fullFilePath);
			} else {
				$this->Owner->addError('filePath', 'File uploaded failed!');
			}
		} else {
			if ($this->Owner->isNewRecord) {
				#$this->Owner->addError('filePath', 'No file uploaded!');
				Yii::trace('No file uploaded!');
			}
		}
	}

	public function beforeDelete($event) {
		$this->prepareDataDirectory();
		$this->deleteFile($this->Owner->path);
	}
	
	private function deleteFile($path) {
		$fileToDelete = $this->_baseDataPath . DIRECTORY_SEPARATOR . $path;
		if (is_file($fileToDelete)) {
			if (!rename($fileToDelete, $this->_trashPath . DIRECTORY_SEPARATOR . basename($path))) {
				Yii::log("Error while moving file '" . $path . "' to trash.", CLogger::LEVEL_WARNING);
			} else {
				Yii::log("Moved file '" . $path . "' to trash.", CLogger::LEVEL_INFO);
			}
		} else {
			Yii::log("Error file '" . $path . "' could not be deleted. File not found.", CLogger::LEVEL_WARNING);
		}
	}
	
	private function prepareDataDirectory() {
		$this->_baseDataPath = Yii::getPathOfAlias($this->dataAlias);
		$this->_fullDataPath = Yii::getPathOfAlias($this->dataAlias) . DIRECTORY_SEPARATOR . $this->dataSubdirectory;
		$this->_relativeDataPath = $this->dataSubdirectory;
		if (!is_dir($this->_fullDataPath)) {
			mkdir($this->_fullDataPath);
			chmod($this->_fullDataPath, 0777);
		}

		$this->_trashPath = Yii::getPathOfAlias($this->trashAlias);
		if (!is_dir($this->_trashPath)) {
			mkdir($this->_trashPath);
			chmod($this->_trashPath, 0777);
		}
	}

}

?>
