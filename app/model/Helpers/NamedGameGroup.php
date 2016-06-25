<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 25.06.2016
 * Time: 10:47
 */

namespace App\Model\Helpers;


use App\Model\Game;
use Nette\Object;
use Nette\Utils\Strings;

class NamedGameGroup extends Object
{
	const WILDCARD = '*';

	private $startLetter;
	private $endLetter;

	/** @var string */
	protected $title;

	/** @var Game[] */
	protected $games;

	/**
	 * NamedGameGroup constructor.
	 * @param string $startLetter
	 * @param string $endLetter
	 */
	public function __construct($startLetter = self::WILDCARD, $endLetter = self::WILDCARD)
	{
		$this->startLetter = $startLetter;
		$this->endLetter = $endLetter;

		$this->games = [];
		$this->title = $this->makeTitle($startLetter, $endLetter);
	}

	private function makeTitle($startLetter, $endLetter)
	{
		if($startLetter == self::WILDCARD || $endLetter == self::WILDCARD){
			return 'Ostatní';
		}

		return $startLetter . ' - ' . $endLetter;
	}

	/**
	 * @param string $title
	 * @return bool
	 */
	public function titleBelongs($title)
	{
		if($this->startLetter == self::WILDCARD || $this->endLetter == self::WILDCARD){
			return true;
		}
		$char = strtoupper(substr($title, 0, 1));


		return ($char >= $this->startLetter && $char <= $this->endLetter);
	}


	/**
	 * @return Game[]
	 */
	public function getGames()
	{
		return $this->games;
	}

	/**
	 * @param Game[] $games
	 */
	public function setGames($games)
	{
		$this->games = $games;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getHandle()
	{
		$s = Strings::webalize($this->title);
		return Strings::substring($s, 0, 4);
	}

	public function addGame($game)
	{
		$this->games[] = $game;
	}

}