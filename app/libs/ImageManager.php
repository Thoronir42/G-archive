<?php

namespace App\Libs;
use Nette\Http\FileUpload;

/**
 * Description of ImageManager
 *
 * @author Stepan
 */
class ImageManager {

	public static function allowedFileTypes(){
		return ["jpg", "jpeg", "png", "gif"];
	}

	const MIN_IMG_SIZE = 150;
	const MAX_IMG_SIZE = 2000;
	const MAX_IMG_FILE_SIZE = 12 * 1024 * 1024;

	protected $imgFolder;

	private $errors;

	public function __construct()
	{
		$this->imgFolder = __DIR__ . "/../../www/images/games/";
		$this->errors = [];
	}

	/**
	 * @param FileUpload[] $files
	 * @return array
	 */
	public function putMany($files){
		$uploads = [];
		foreach($files as $file){
			$result = self::put($file);
			if($result['result']){
				unset($result['result']);
				$uploads[] = $result;
			} else {
				$this->errors[] = $result['message'];
			}
		}
		return $uploads;

	}


	public function put(FileUpload $file) {
		if(!$file->ok){
			$this->errors[] = "Při nahrávání obrázku " . $file->name . " nastala chyba " . $file->error;
			return false;
		}

		// Allow certain file formats
		$fileType = $this->verifyFileType(basename($file->name));
		if (!$fileType) {
			$this->errors[] = "Nahraný soubor " . $file->name . " není jedním z povolených typů: " . implode(", ", self::allowedFileTypes());
			return false;
		}

		// Check if image file is a actual image or fake image
		$dimensionErrors = self::checkImageDimensions($file->temporaryFile);
		if (!empty($dimensionErrors)) {
			$this->errors = $dimensionErrors;
			return false;

		}

		// Check file size
		if (($file_size = $file->size) > self::MAX_IMG_FILE_SIZE) {
			$errors[] =  "Velikost souboru " . ($file_size / 1024) . "kb přesáhla povolený limit " . (self::MAX_IMG_FILE_SIZE/ 1024) . "kb";
			return false;
		}

		// if everything is ok, try to upload file
		$destFile = $this->uniqueFilename($file->name);
		$finalFileName = $this->imgFolder . $destFile;

		if(!$file->move($finalFileName)){
			$errors[] = "Nahraný obrázek se nepodařilo uložit.";
			return false;
		}
		return $destFile;
	}

	//
	// Various checks
	//

	private function checkImageDimensions($tmpName) {
		$check = getimagesize($tmpName);
		$w = $check[0];
		$h = $check[1];

		$errors = [];

		if ($check === false) {
			return ['result' => false, 'message' => "Nahraný soubor není obrázek"];
		}
		if($w < self::MIN_IMG_SIZE || $w > self::MAX_IMG_SIZE){
			$errors[] = "Šířka obrázku musí být větší než ". self::MIN_IMG_SIZE . " a menší než " . self::MAX_IMG_SIZE . " pixelů";
		}
		if($h < self::MIN_IMG_SIZE || $h > self::MAX_IMG_SIZE){
			$errors[] = "Výška obrázku musí být větší než ". self::MIN_IMG_SIZE . " a menší než " . self::MAX_IMG_SIZE . " pixelů";
		}

		return $errors;
	}

	/**
	 * @param string $file
	 * @return bool|string
	 */
	private function verifyFileType($file) {
		$imageFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		foreach (self::allowedFileTypes() as $ft) {
			if ($imageFileType == $ft) {
				return $ft;
			}
		}

		return false;
	}

	public function uniqueFilename($target_file) {
		if (file_exists($this->imgFolder . $target_file)) {
			$renameAttempt = 0;
			$parts = explode('.', $target_file);
			$name = $parts[0];
			$suffix = $parts[1];
			do {
				$reName = $name . ( ++$renameAttempt) . ".$suffix";
			} while (file_exists($this->imgFolder . $reName));
			$target_file = $reName;
		}
		return $target_file;
	}

	//
	//  Accessors
	//

	public function getErrors(){
		return $this->errors;
	}
	
	
}
