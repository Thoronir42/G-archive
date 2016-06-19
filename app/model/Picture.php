<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn("picture_type", type="string")
 * @ORM\DiscriminatorMap({"regular" = "Picture", "game" = "GamePicture", "platform" = "PlatformPicture"})
 */
class Picture extends BaseEntity {

	use Doctrine\Entities\Attributes\Identifier;

	/** @ORM\Column(type="string", length=200) */
	var $path;

	/** @ORM\Column(type="string", length=1000, nullable=true) */
	var $description;
}
