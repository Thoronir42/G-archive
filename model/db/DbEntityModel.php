<?php
namespace model\db;

/**
 * Description of DbEntityModel
 *
 * @author Stepan
 */
class DbEntityModel {
	
	public static function fromPost() {
		return self::createInstance();
	}
	
	protected static function createInstance($class = null){
		if($class == null){
			return null;
		}
		$rc = new \ReflectionClass($class);
		$instance = $rc->newInstance(null);
		$rc->getConstructor()->invoke($instance);
		
		$fields = $rc->getProperties();
		foreach ($fields as $field) {
			$fName = $field->getName();
			$instance->$fName = filter_input(INPUT_POST, $fName);
		}
		return $instance;
	}
	
	var $misc;
	
	public function __construct() {
		$this->misc = [];
	}
	
	public function __isset($name) {
		return !is_null($this->misc) && array_key_exists($name, $this->misc);
	}
	
	public function __get($name) {
		if(isset($this->misc[$name])){
			return $this->misc[$name];
		}
	}

	public function __set($name, $value) {
		$this->misc[$name] = $value;
	}
	
	public function toArray($includeMisc = false) {
		return $this->creaArray();
	}
	
	protected function createArray($includeMisc = false, $class = null){
		if($class == null){
			return null;
		}
		$return = [];
		$rc = new \ReflectionClass(self::class);
		$fields = $rc->getProperties();
		foreach ($fields as $field) {
			$fName = $field->getName();
			if ($includeMisc && $fName == "misc") {
				continue;
			}
			$return[$fName] = $this->$fName;
		}
		return $return;
	}

	
}
