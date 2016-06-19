<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 19.06.2016
 * Time: 10:14
 */

namespace App\Model\Services;


use App\Model\GamePicture;
use Kdyby\Doctrine\EntityManager;

class GamePictures extends BaseService
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(GamePicture::class));
	}
}