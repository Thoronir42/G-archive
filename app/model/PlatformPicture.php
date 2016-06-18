<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 17:34
 */

namespace App\Model;


class PlatformPicture extends Picture
{
	/**
	 * @var Game
	 * @ORM\ManyToOne(targetEntity="Platform")
	 */
	protected $game;
}