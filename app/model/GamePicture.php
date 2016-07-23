<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @property		Game $game
 * @ORM\Entity
 */
class GamePicture extends Picture
{
	/**
	 * @var Game
	 * @ORM\ManyToOne(targetEntity="Game")
	 */
	protected $game;
}
