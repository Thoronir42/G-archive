<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture")
 */
class Picture extends BaseEntity {

	use Doctrine\Entities\Attributes\Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="Game")
	 * @ORM\JoinColumn(name="id_game", referencedColumnName="id")
	 */
	var $game;

	/** @ORM\Column(type="string", length=100) */
	var $path;
	/** @ORM\Column(type="string", length=100) */
	var $description;

}
