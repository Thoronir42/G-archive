<?php
namespace libs;

use PDO,
		model\db\Game,
		model\db\VGame,
		model\db\State;


class PDOwrapper{
    /** @var PDO */
    private $connection;
    
	/**
	 * 
	 * @param array $cfg
	 * @return \PDOwrapper
	 */
    public static function getConnection($cfg){
        $cfg['password'] = isset($cfg['password']) ? $cfg['password'] : null;
        $pdo = new PDO("mysql:host=$cfg[host];dbname=$cfg[db_name];charset=utf8",
				$cfg['user'],
				$cfg['password'],
				 array());
		return new PDOwrapper($pdo);
    }
	
	/**
	 * 
	 * @param PDO $pdo
	 */
	private function __construct($pdo) {
		$this->connection = $pdo;
	}
	
	public function getGames(){
		$result = $this->connection->query("SELECT * FROM games_human")
				->fetchAll(PDO::FETCH_CLASS, VGame::class);
		return $result;
	}
	
	public function getStates(){
		$result = $this->connection->query("SELECT * FROM state ORDER BY freshness DESC")
				->fetchAll(PDO::FETCH_CLASS, State::class);
		return $result;
	}
	
	/**
	 * 
	 * @return VGame
	 */
	public function fetchGame($id){
		if(!is_numeric($id)){
			return false;
		}
		$statement = $this->connection->prepare("SELECT * FROM game
				WHERE id_game = $id");
		echo $statement->queryString."\n";
		if(!$statement->execute()){
			return false;
		} 
		$result = $statement->fetchObject(Game::class);
		
		return $result;
	}
	
	public function insertGame($params){
		$statement = $this->connection->prepare("INSERT INTO game(name, picture, cartridge_state, manual_state, packing_state, completion, affection)
    VALUES(:name, :picture, :cartridge_state, :manual_state, :packing_state, :completion, :affection)");
		$result = $statement->execute($params);
		return $result;
	}
	
	public function editGame($params){
		$statement = $this->connection->prepare("UPDATE game SET 
			name = :name,
			picture = :picture,
			cartridge_state = :cartridge_state,
			manual_state = :manual_state,
			packing_state = :packing_state,
			completion = :completion,
			affection = :affection
				WHERE game_id = :game_id");
		$result = $statement->execute($params);
		echo $result;
	}
	
	public function insertImage($params){
		$statement = $this->connection->prepare("INSERT INTO picture(id_game, description, picture_path)
    VALUES(:id_game, :description, :picture_path)");
		$statement->execute($params);
		
		return $this->connection->lastInsertId("picture");
	}
	public function linkPicture($id_picture, $id_game){
		$statement = $this->connection->prepare("UPDATE `g-archive`.`picture` SET `id_game` = :id_game WHERE `picture`.`id_picture` = :id_picture;");
		$params = ["id_picture" => $id_picture, "id_game" => $id_game];
		$statement->execute($params);
	}
	
}