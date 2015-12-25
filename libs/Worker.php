<?php
namespace libs;

use config\GameParams;

/**
 * Description of Worker
 *
 * @author Stepan
 */
class Worker {

	const DEF_ACTION = "vypis";

	/** @var URLgen */
	var $URLgen;
	
	/** @var PDOwrapper */
	var $pdoWrapper;
	
	/** @var array */
	var $template;
	
	/**
	 * 
	 * @param PDOwrapper $p
	 * @param URLgen $u
	 */
	public function __construct($p, $u) {
		$this->pdoWrapper = $p;
		$this->URLgen = $u;
		$this->template = [
			"css" => [],
			"js" => [],
			'ugen' => $u,
		];
		
		$this->template["menu"] = $this->buildMenu();
	}
	
	public function startUp(){
		$this->layout = "layout.twig";
		$this->template["css"][] = "default.css";
		$this->template["css"][] = "game.css";
		
		$this->template["max_affection"] = GameParams::MAX_RATING;
	}
	
	private function buildMenu(){
		$items = [
			["action" => "vypis", "title" => "Výpis her"],
			["action" => "vlozeni", "title" => "+ Zadat novou hru"],
			["action" => "obrazky", "title" => "Seznam obrázků"],
		];
		return $items;
	}
	
	public function setActiveMenuItem($action) {
		$items = $this->template['menu'];
		foreach($items as $key => $i){
			if($i['action'] == $action){
				$items[$key]["active"] = true;
				break;
			}
		}
		$this->template['menu'] = $items;
	}
	
	
	public function renderVypis(){
		$games =  $this->pdoWrapper->getGames();
		$this->template['columns'] = $this->gamesToCols($games, 3);
		$this->template['tmp_editLink'] = ['action' => 'vlozeni', 'id' => 0];
		$this->template['tmp_remLink'] = ['action' => 'smazani', 'id' => 0];
	}
	
	/**
	 * 
	 * @param array $games
	 * @return array
	 */
	private function gamesToCols($games, $colCount){
		$columns = [];
		for($i = 0; $i < $colCount; $i++){
			$columns[$i] = [];
		}
		$i = 0;
		
		foreach($games as $key => $game){
			$columns[$i][] = $this->verifyProperties($game);
			$i = ($i + 1) % $colCount;
		}
		return ['count' => $colCount, 'list' => $columns];
	}
	
	/**
	 * 
	 * @param \Model\VGame $game
	 * @return \Model\VGame
	 */
	private function verifyProperties($game){
		
		$game->completion *= GameParams::COMPLETION_RANGE_ACCURACY;
		return $game;
	}
	
	
	public function renderVlozeni(){
		$id = filter_input(INPUT_GET, "id");
		$uprava = isset($id);
		
		$game = $uprava ? $this->pdoWrapper->fetchGame($id) : \model\db\Game::fromPost();
				
		if($uprava && !$game){
			echo "Nebylo mozné načíst hru #$id";
			return;
		}
		
		$game->completion = $game->completion * GameParams::COMPLETION_RANGE_ACCURACY;
		
		$this->template['default'] = $game;
		
		
		$this->template['states'] = $this->pdoWrapper->getStates();
		$this->template['range_accuracy'] = GameParams::COMPLETION_RANGE_ACCURACY;
		
		$this->template['formAction'] = $uprava ? "?action=uprav" : "?action=vloz";
		$this->template['submitLabel']= $uprava ? "Upravit" : "Přidat";
		$this->template['subtitle'] =   $uprava ? "Úprava detailů hry $game->name" : "Vložení nové hry";
	}
	
	public function doVloz(){
		$pic = ["picture_path" => $this->handleFile(),
				"description" => filter_input(INPUT_POST, "picture_description"),
				"id_game" => NULL];
		if(!$pic['picture_path']){
			echo "Chyba při nahrávání obrázku";
			$this->redirect("vlozeni");
		}
		$id_picture = $this->pdoWrapper->insertImage($pic);
		$game = $this->prepareGameAsArray($id_picture);
		$id_game = $this->pdoWrapper->insertGame($game);
		$this->pdoWrapper->linkPicture($id_picture, $id_game);
		
		$this->redirect("vypis");
	}
	
	public function doUprav(){
		$game = $this->prepareGameAsArray();
		$this->pdoWrapper->editGame($game);
		
		$this->redirect("vypis");
	}
	
	/**
	 * Reads game info from post and returns it's instance
	 * @return \Model\Game 
	 */
	private function prepareGameAsArray($id_picture = null){
		$game = \Model\Game::fromPost()->toArray();
		if(!$id_picture){
			$game['picture'] = $id_picture;
			unset($game['id_game']);
		}
		unset($game['misc']);
		$game['completion'] = $game['completion'] * 1.0 / GameParams::COMPLETION_RANGE_ACCURACY;
	}
	
	private function handleFile(){
		$target_dir = "images/";
		$target_file =$this->getNonExistingFilename(basename($_FILES["picture"]["name"]), $target_dir);
		$imageFileType = pathinfo($target_dir.$target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["picture"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
			} else {
				echo "File is not an image.";
				$uploadError = 1;
			}
		}
		
		// Check file size
		if ($_FILES["picture"]["size"] > 50000000) {
			echo "Sorry, your file is too large.";
			$uploadError = 2;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadError = 3;
		}
		// Check if $uploadOk is set to 0 by an error
		if (isset($uploadError)) {
			echo "File upload error: $uploadError";
			return false;
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_dir.$target_file)) {
				echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded as $target_file.<br/>";
			} else {
				echo "Sorry, there was an error uploading your file.<br/>";
			}
		}
		return $target_file;
	}
	
	public function renderObrazky(){
		$this->template["subtitle"] = "loljk... maby latr. Defintly";
	}
	
	
	
	
	public function redirect($action){
		$location = $this->URLgen->getUrl(["action" => $action], false);
        \header("Location: /$location");
		\header("Connection: close");
    }

	public function getNonExistingFilename($target_file, $target_dir) {
		if (file_exists($target_dir.$target_file)) {
			$renameAttempt = 0;
			$parts = explode('.', $target_file);
			$name = $parts[0];
			$suffix = $parts[1];
			do{
				$reName = $name.(++$renameAttempt).".$suffix";
			} while (file_exists($target_dir.$reName));
			$target_file = $reName;
		}
		return $target_file;
	}

}
