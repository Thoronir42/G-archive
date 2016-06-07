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
	 * @var Game
	 * @ORM\ManyToOne(targetEntity="Game")
	 * @ORM\JoinColumn(name="id_game", referencedColumnName="id")
	 */
	var $game;

	/** @ORM\Column(type="string", length=100) */
	var $path;

	/** @ORM\Column(type="string", length=100, nullable=true) */
	var $description;

	public function isPrimary(){
		if(!$this->game){
			return false;
		}
		return $this->game->primary_picture == $this;
	}

}
