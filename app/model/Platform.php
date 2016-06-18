<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 10:47
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="platform")
 */
class Platform extends BaseEntity
{
	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="string", length=420) */
	protected $name;

	/** @ORM\Column(type="integer") */
	protected $count;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="GamePicture", mappedBy="game")
	 */
	protected $pictures;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Game", mappedBy="platform")
	 */
	protected $games;

	public function __construct()
	{
		$this->pictures = new ArrayCollection;
		$this->games = new ArrayCollection;
	}
}