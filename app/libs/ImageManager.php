<?php

namespace App\Libs;
use App\Model\Game;
use App\Model\GamePicture;
use App\Model\Picture;
use App\Model\PlatformPicture;
use Nette\Http\FileUpload;
use Nette\InvalidArgumentException;

/**
 * Description of ImageManager
 *
 * @author Stepan
 */
class ImageManager {

	public static function allowedFileTypes(){
		return ["jpg", "jpeg", "png", "gif"];
	}

	const
		MODE_DEFAULT = 'default',
		MODE_GAME = 'game',
		MODE_PLATFORM = 'platform';


	const MIN_IMG_SIZE = 150;
	const MAX_IMG_SIZE = 2000;
	const MAX_IMG_FILE_SIZE = 12 * 1024 * 1024;

	private static $BASE_DIR = __DIR__ . "/../../www/images/";
	
	protected $imgFolder;

	private $errors;

	public function __construct()
	{
		$this->imgFolder = $this->getDir(self::MODE_DEFAULT);
		$this->errors = [];
	}

	public function setMode($mode){
		$this->imgFolder = $this->getDir($mode);

		if(!is_dir($this->imgFolder)){
			mkdir($this->imgFolder);
		}
	}

	private function getDir($mode)
	{
		switch ($mode){
			default:
			case self::MODE_DEFAULT:
				return self::$BASE_DIR;
			case self::MODE_GAME:
				return self::$BASE_DIR . 'games/';
			case self::MODE_PLATFORM:
				return self::$BASE_DIR . 'platforms/';
		}
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

	private function uniqueFilename($target_file) {
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


	/**
	 * @param Picture|GamePicture|PlatformPicture $picture
	 */
	public function delete($picture){
		$dir = $this->getDir($this->getMode($picture));
		@unlink($dir . $picture->path);
	}

	private function getMode($picture)
	{
		if($picture instanceof Picture){
			return self::MODE_DEFAULT;
		}
		if($picture instanceof GamePicture){
			return self::MODE_GAME;
		}
		if($picture instanceof PlatformPicture){
			return self::MODE_PLATFORM;
		}
		throw new InvalidArgumentException("Invalid use of " .type_class($picture). " in ImageManager");
	}


}
