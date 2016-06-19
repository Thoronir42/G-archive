<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 17:34
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PlatformPicture extends Picture
{
	/**
	 * @var Platform
	 * @ORM\OneToOne(targetEntity="Platform", inversedBy="picture")
	 */
	protected $platform;

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