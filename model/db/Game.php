<?php

namespace model\db;

/**
 * Description of Game
 *
 * @author Stepan
 */
class Game extends DbEntityModel {

	public static function fromPost() {
		return self::createInstance(self::class);
	}
	
	
	var $id_game;
	var $name;
	var $cartridge_state;
	var $manual_state;
	var $packing_state;
	var $completion;
	var $affection;

	

	public function __construct() {
		parent::__construct();
	}
	
	public function getCompletionPct(){
		return $this->completion * \config\GameParams::COMPLETION_FIX;
	}
	
	public function getCompletionVal(){
		return $this->completion * \config\GameParams::COMPLETION_RANGE_ACCURACY;
	}
	
	public function toArray($includeMisc = false) {
		return $this->createArray($includeMisc, self::class);
	}
	
}
