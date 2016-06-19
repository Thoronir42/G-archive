<?php

namespace App\Model;

use App;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine;

/**
 * @ORM\Entity
 * @ORM\Table(name="state")
 */
class State extends BaseEntity
{
	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="integer") */
	var $sequence;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	var $label;

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
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param mixed $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	function __toString()
	{
		return "" . $this->label;
	}


}
