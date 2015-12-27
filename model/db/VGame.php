<?php

namespace model\db;

/**
 * Description of game
 *
 * @author Stepan
 */
class VGame extends Game {

	var $picture_path;
	var $picture_description;

	public function __construct() {
		parent::__construct();
		$this->misc['images'] = [];
	}

	/**
	 * 
	 * @param Image $i
	 */
	public function addImage($i) {
		$this->misc['images'][] = $i;
	}

}
