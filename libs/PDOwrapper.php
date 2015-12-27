<?php

namespace libs;

use PDO,
	model\db\Image,
	model\db\Game,
	model\db\VGame,
	model\db\State;

class PDOwrapper {

	/** @var PDO */
	private $connection;

	/**
	 * 
	 * @param array $cfg
	 * @return \PDOwrapper
	 */
	public static function getConnection($cfg) {
		$cfg['password'] = isset($cfg['password']) ? $cfg['password'] : null;
		$pdo = new PDO("mysql:host=$cfg[host];dbname=$cfg[db_name];charset=utf8", $cfg['user'], $cfg['password'], array());
		return new PDOwrapper($pdo);
	}

	/**
	 * 
	 * @param PDO $pdo
	 */
	private function __construct($pdo) {
		$this->connection = $pdo;
	}

	/**
	 * 
	 * @return VGame[]
	 */
	public function getGames() {
		$result = $this->connection->query("SELECT * FROM games_human")
				->fetchAll(PDO::FETCH_CLASS, VGame::class);
		return $result;
	}

	public function getStates() {
		$result = $this->connection->query("SELECT * FROM state ORDER BY freshness DESC")
				->fetchAll(PDO::FETCH_CLASS, State::class);
		return $result;
	}

	/**
	 * 
	 * @return Image[]
	 */
	public function getImages() {
		$result = $this->connection->query("SELECT * FROM picture ORDER BY id_game DESC")
				->fetchAll(PDO::FETCH_CLASS, Image::class);
		return $result;
	}

	/**
	 * 
	 * @return VGame
	 */
	public function fetchGame($id) {
		if (!is_numeric($id)) {
			return false;
		}
		$statement = $this->connection->prepare("SELECT * FROM game
				WHERE id_game = $id");
		if (!$statement->execute()) {
			return false;
		}
		$result = $statement->fetchObject(Game::class);

		return $result;
	}

	public function insertGame($params) {
		$statement = $this->connection->prepare("INSERT INTO game(name, picture, cartridge_state, manual_state, packing_state, completion, affection)
    VALUES(:name, :picture, :cartridge_state, :manual_state, :packing_state, :completion, :affection)");
		$result = $statement->execute($params);
		return $result;
	}

	public function editGame($params) {
		$statement = $this->connection->prepare("UPDATE game SET 
			name = :name,
			picture = :picture,
			cartridge_state = :cartridge_state,
			manual_state = :manual_state,
			packing_state = :packing_state,
			completion = :completion,
			affection = :affection
				WHERE id_game = :id_game");
		$result = $statement->execute($params);
		return $result;
	}

	public function insertImage($params, $mult = false) {
		$statement = $this->connection->prepare("INSERT INTO picture ".
			"(id_game,  picture_path) ".
	 "VALUES(:id_game, :picture_path)");
		if($mult){
			$err = 0;
			foreach($params as $p){
				$res = $statement->execute($p);
				if(!$res){
					$err++;
				}
			}
		} else {
			if ($statement->execute($params)) {
				return $this->connection->lastInsertId("picture");
			} else{
				var_dump($statement->errorInfo());
				return false;
			}
		}
		
	}

	public function linkPicture($id_picture, $id_game) {
		$statement = $this->connection->prepare("UPDATE `g-archive`.`picture` SET `id_game` = :id_game WHERE `picture`.`id_picture` = :id_picture;");
		$params = ["id_picture" => $id_picture, "id_game" => $id_game];
		$statement->execute($params);
	}

	public function getPicturesFor($id) {
		$statement = $this->connection->prepare("SELECT * FROM picture WHERE id_game = :id");
		if ($statement->execute(['id' => $id])) {
			return $statement->fetchAll(PDO::FETCH_CLASS, \model\db\Image::class);
		}
		return null;
	}

}
