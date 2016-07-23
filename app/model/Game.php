<?php

namespace App\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game extends BaseEntity {

	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="string", length=420) */
	var $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Platform")
	 * @ORM\JoinColumn(name="platform", referencedColumnName="id")
	 */
	protected $platform;

	/**
	 * @ORM\ManyToOne(targetEntity="State")
	 * @ORM\JoinColumn(name="cartridge_state", referencedColumnName="id")
	 */
	var $cartridge_state;

	/**
	 * @ORM\ManyToOne(targetEntity="State")
	 * @ORM\JoinColumn(name="manual_state", referencedColumnName="id")
	 */
	var $manual_state;

	/**
	 * @ORM\ManyToOne(targetEntity="State")
	 * @ORM\JoinColumn(name="packing_state", referencedColumnName="id")
	 */
	var $packing_state;

	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Tag")
	 * @ORM\JoinTable(name="game_tags",
	 *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	var $completion_tags;

	/** @ORM\Column(type="integer", length=100, nullable=true) */
	var $affection;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="GamePicture", mappedBy="game")
	 */
	var $pictures;

	/**
	 * @var Picture
	 * @ORM\OneToOne(targetEntity="Picture")
	 * @ORM\JoinColumn(name="primary_picture", referencedColumnName="id")
	 */
	var $primary_picture;

	public function __construct() {
		parent::__construct();

		$this->pictures = new ArrayCollection();
		$this->completion_tags = new ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return State
	 */
	public function getCartridgeState()
	{
		return $this->cartridge_state;
	}

	/**
	 * @param State $cartridge_state
	 */
	public function setCartridgeState(State $cartridge_state)
	{
		$this->cartridge_state = $cartridge_state;
	}

	/**
	 * @return State
	 */
	public function getManualState()
	{
		return $this->manual_state;
	}

	/**
	 * @param State $manual_state
	 */
	public function setManualState(State $manual_state)
	{
		$this->manual_state = $manual_state;
	}

	/**
	 * @return State
	 */
	public function getPackingState()
	{
		return $this->packing_state;
	}

	/**
	 * @param State $packing_state
	 */
	public function setPackingState(State $packing_state)
	{
		$this->packing_state = $packing_state;
	}

	/**
	 * @return mixed
	 */
	public function getAffection()
	{
		return $this->affection;
	}

	/**
	 * @param mixed $affection
	 */
	public function setAffection($affection)
	{
		$this->affection = $affection;
	}

	/**
	 * @return mixed
	 */
	public function getPictures()
	{
		return $this->pictures;
	}

	/**
	 * @param mixed $pictures
	 */
	public function setPictures($pictures)
	{
		$this->pictures = $pictures;
	}

	/**
	 * @return mixed
	 */
	public function getPrimaryPicture()
	{
		return $this->primary_picture;
	}

	/**
	 * @param mixed $primary_picture
	 */
	public function setPrimaryPicture($primary_picture)
	{
		$this->primary_picture = $primary_picture;
	}

	/**
	 * @return mixed
	 */
	public function getCompletionTags()
	{
		return $this->completion_tags;
	}

	/**
	 * @param mixed $completion_tags
	 */
	public function setCompletionTags($completion_tags)
	{
		$this->completion_tags = $completion_tags;
	}

	/**
	 * @return Platform
	 */
	public function getPlatform()
	{
		return $this->platform;
	}

	/**
	 * @param Platform $platform
	 */
	public function setPlatform($platform)
	{
		$this->platform = $platform;
	}




	
}
