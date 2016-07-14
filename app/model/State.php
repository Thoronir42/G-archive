<?php

namespace App\Model;

use App;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine;
use Nette\Utils\Strings;

/**
 * @property 		int		$id
 * @property		string	$label;
 * @property-read	string	$class;
 *
 * @ORM\Entity
 * @ORM\Table(name="state")
 */
class State extends BaseEntity
{
	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="integer") */
	var $sequence = 1;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	var $label;

	/** @ORM\Column(type="boolean") */
	var $deleted = false;

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

	public function getClass(){
		return Strings::webalize($this->label);
	}


}
