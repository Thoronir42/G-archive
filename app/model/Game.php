<?php

namespace App\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine;
use Doctrine\ORM\Mapping as ORM;


use App\Model\Services\Games;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game extends BaseEntity {

	use Doctrine\Entities\Attributes\Identifier;

	/**
	 * 
	 * @return Game
	 */
	public static function fromPost() {
		return self::createInstance(self::class);
	}

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
	 * @ORM\Column(type="float")
	 */
	var $completion;

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
		$this->pictures = new ArrayCollection;
	}

	public function getCompletionPct(){
		return $this->completion * Games::getSettings()->getCompletionFix();
	}
	
	public function getCompletionVal(){
		return $this->completion * Games::getSettings()->getCompletionRange();
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
	public function getCompletion()
	{
		return $this->completion;
	}

	/**
	 * @param mixed $completion
	 */
	public function setCompletion($completion)
	{
		if(0 > $completion || $completion > 1){
			throw new Doctrine\InvalidArgumentException("Completion has to be within interval <0; 1>");
		}
		$this->completion = $completion;
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


	
}
