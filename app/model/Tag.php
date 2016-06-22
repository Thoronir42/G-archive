<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 22.06.2016
 * Time: 19:43
 */

namespace App\Model;

use Kdyby\Doctrine;
use Doctrine\ORM\Mapping as ORM;

use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag extends BaseEntity
{
	use Identifier;

	/** @ORM\Column(type="string", length=420) */
	var $title;

	/** @ORM\Column(type="string", length=420, nullable=true) */
	var $type;
}