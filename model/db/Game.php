<?php

namespace model\db;

/**
 * Description of Game
 *
 * @author Stepan
 */
class Game extends DbEntityModel {

	var $id_game;
	var $name;
	var $cartridge_state;
	var $manual_state;
	var $packing_state;
	var $completion;
	var $affection;

	public static function fromPost() {
		$instance = new Game();

		$rc = new \ReflectionClass(self::class);
		$fields = $rc->getProperties();
		foreach ($fields as $field) {
			$fName = $field->getName();
			$instance->$fName = filter_input(INPUT_POST, $fName);
		}
		return $instance;
	}

	public function toArray() {
		$return = [];
		$rc = new \ReflectionClass(self::class);
		$fields = $rc->getProperties();
		foreach ($fields as $field) {
			$fName = $field->getName();
			$return[$fName] = $this->$fName;
		}
		return $return;
	}

}
