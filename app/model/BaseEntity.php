<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

use Kdyby\Doctrine;

class BaseEntity extends Doctrine\Entities\BaseEntity{

	/**
	 * 
	 * @return BaseEntity
	 */
	public static function fromPost() {
		return self::createInstance();
	}

	protected static function createInstance($class = null) {
		if ($class == null) {
			return null;
		}
		$rc = new \ReflectionClass($class);
		$instance = $rc->newInstance(null);
		$rc->getConstructor()->invoke($instance);

		$fields = $rc->getProperties();
		foreach ($fields as $field) {
			$name = $field->getName();
			$instance->$name = filter_input(INPUT_POST, $name);
		}
		return $instance;
	}

	public function toArray() {
		$array = [];
		foreach ($this as $field => $value) {
			$array[$field] = $value;
		}
		return $array;
	}

}
