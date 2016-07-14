<?php

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Kdyby\Doctrine;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * @property 	int		$id
 * Class BaseEntity
 * @package App\Model
 */
class BaseEntity
{
	use MagicAccessors;

	public function toArray()
	{
		$array = [];
		foreach ($this as $field => $value) {
			if ($value instanceof BaseEntity) {
				$value = $value->id;
			} else if ($value instanceof Collection){
				$value = array_map(function(BaseEntity $item){
					return $item->id;
				}, $value);
			}

			$array[$field] = $value;

		}
		return $array;
	}

}
