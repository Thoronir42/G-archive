<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 14:33
 */

namespace App\Model;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game_picture")
 */
class GamePicture extends Picture
{
	/**
	 * @var Game
	 * @ORM\ManyToOne(targetEntity="Game")
	 */
	protected $game;
}