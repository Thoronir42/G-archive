<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 10:47
 */

namespace App\Model;

use App\Model\Helpers\GroupedGames;
use App\Model\Helpers\NamedGameGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @ORM\Entity
 * @ORM\Table(name="platform")
 */
class Platform extends BaseEntity
{
	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="string", length=420) */
	var $title;

	/** @ORM\Column(type="integer") */
	var $count;

	/**
	 * @var PlatformPicture
	 * @ORM\OneToOne(targetEntity="PlatformPicture", mappedBy="platform")
	 */
	var $picture;

	/** @ORM\Column(type="integer") */
	var $sequence = 0;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Game", mappedBy="platform")
	 */
	var $games;

	/** @var GroupedGames  */
	var $groupedGames;

	public function __construct()
	{
		$this->games = new ArrayCollection;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * @param mixed $count
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}

	/**
	 * @return PlatformPicture
	 */
	public function getPicture()
	{
		return $this->picture;
	}

	/**
	 * @param PlatformPicture $picture
	 */
	public function setPicture($picture)
	{
		$this->picture = $picture;
	}

	/**
	 * @return mixed
	 */
	public function getSequence()
	{
		return $this->sequence;
	}

	/**
	 * @param mixed $sequence
	 */
	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getGames()
	{
		return $this->games;
	}

	/**
	 * @param ArrayCollection $games
	 */
	public function setGames($games)
	{
		$this->games = $games;
	}

	public function getGroupedGames(){
		if(!$this->groupedGames){
			$this->groupedGames = new GroupedGames($this->games);
		}

		return $this->groupedGames;
	}

	public function getHandle()
	{
		$s = Strings::webalize($this->title);
		return Strings::substring($s, 0, 4);
	}

	
}