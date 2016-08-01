<?php

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Kdyby\Doctrine;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * Class BaseEntity
 * @package App\Model
 *
 * @property 	int		$id
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
				}, $value->toArray());
			}

			$array[$field] = $value;

		}
		return $array;
	}

}
