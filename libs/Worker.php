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
	const GAME_COLS = 4; // Musí být dělitel 12

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
			"css" => ["default.css", "game.css"],
			"js" => [],
			'ugen' => $u,
		];

		$this->layout = "layout.twig";
		$this->template["max_affection"] = GameParams::MAX_RATING;
		$this->template["menu"] = $this->buildMenu();
	}

	private function buildMenu() {
		$items = [
			["action" => "vypis", "title" => "Výpis her"],
			["action" => "vlozeni", "title" => "+ Zadat novou hru"],
			["action" => "obrazky", "title" => "Seznam obrázků"],
		];
		return $items;
	}

	public function setActiveMenuItem($action) {
		$items = $this->template['menu'];
		foreach ($items as $key => $i) {
			if ($i['action'] == $action) {
				$items[$key]["active"] = true;
				break;
			}
		}
		$this->template['menu'] = $items;
	}

	public function renderVypis() {
		$games = $this->pdoWrapper->getGames();
		$this->template['columns'] = $this->gamesToCols($games, self::GAME_COLS);
		$this->template['tmp_editLink'] = ['action' => 'vlozeni', 'id' => 0];
		$this->template['tmp_remLink'] = ['action' => 'smazani', 'id' => 0];
	}

	/**
	 * 
	 * @param array $games
	 * @return array
	 */
	private function gamesToCols($games, $colCount) {
		$columns = [];
		for ($i = 0; $i < $colCount; $i++) {
			$columns[$i] = [];
		}
		$i = 0;

		foreach ($games as $key => $game) {
			$columns[$i][] = $game;
			$i = ($i + 1) % $colCount;
		}
		return ['count' => $colCount, 'list' => $columns];
	}

	public function renderVlozeni() {
		$this->template['css'][] = "input-file.css";
		$this->template['js'][] = "input-file.js";
		$this->template['js'][] = 'vlozeni.js';
		$this->template['css'][] = 'vlozeni.css';
		$id = filter_input(INPUT_GET, "id");
		$uprava = isset($id);

		if ($uprava) {
			$game = $this->pdoWrapper->fetchGame($id);
		} else {
			$game = \model\db\Game::fromPost();
			$game->new = true;
		}

		if ($uprava && !$game) {
			echo "Nebylo mozné načíst hru #$id";
			return;
		}

		$this->template['default'] = $game;
		$this->template['pictures'] = $this->pdoWrapper->getPicturesFor($id);

		$this->template['states'] = $this->pdoWrapper->getStates();
		$this->template['range_accuracy'] = GameParams::COMPLETION_RANGE_ACCURACY;

		$this->template['formAction'] = $uprava ? "?action=uprav" : "?action=vloz";
		$this->template['submitLabel'] = $uprava ? "Upravit" : "Přidat";
		$this->template['subtitle'] = $uprava ? "Úprava detailů hry $game->name" : "Vložení nové hry";
	}

	public function doVloz() {
		$p = \model\ImageManager::put("picture");
		if (!$p['result']) {
			echo $p['message'];
			echo "<a href=\"" . $this->URLgen->url(['action' => 'vypis']) . "\">Pokračovat na výpis</a>";
			die;
		}

		$pic = ["picture_path" => $picture_path,
			"description" => filter_input(INPUT_POST, "picture_description"),
			"id_game" => NULL];
		if (!$pic['picture_path']) {
			echo "Chyba při nahrávání obrázku";
			$this->redirect("vlozeni");
		}
		$id_picture = $this->pdoWrapper->insertImage($pic);
		$game = $this->prepareGameAsArray($id_picture);
		$id_game = $this->pdoWrapper->insertGame($game);
		$this->pdoWrapper->linkPicture($id_picture, $id_game);

		$this->redirect("vypis");
	}

	public function doUprav() {
		$game = $this->prepareGameAsArray();
		$this->pdoWrapper->editGame($game);

		$this->redirect("vypis");
	}

	/**
	 * Reads game info from post and returns it's instance
	 * @return \Model\db\Game 
	 */
	private function prepareGameAsArray($id_picture = null) {
		$game = \model\db\Game::fromPost()->toArray();
		if (!$id_picture) {
			$game['picture'] = $id_picture;
			unset($game['id_game']);
		}
		unset($game['misc']);
		$game['completion'] = $game['completion'] * 1.0 / GameParams::COMPLETION_RANGE_ACCURACY;
		return $game;
	}

	public function renderObrazky() {
		$this->template["subtitle"] = "loljk... maby latr. Defintly";
	}

	public function redirect($action) {
		$location = $this->URLgen->url(["action" => $action], false);
		\header("Location: $location");
		\header("Connection: close");
	}

}
