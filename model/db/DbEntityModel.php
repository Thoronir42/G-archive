<?php
namespace Model;

/**
 * Description of DbEntityModel
 *
 * @author Stepan
 */
class DbEntityModel {
	var $misc;
	
	public function __construct() {
		$this->misc = [];
	}
	
	public function __isset($name) {
		return array_key_exists($name, $this->misc);
	}
	
	public function __get($name) {
		if(isset($this->misc[$name])){
			return $this->misc[$name];
		}
	}

	public function __set($name, $value) {
		$this->misc[$name] = $value;
	}

	
}
