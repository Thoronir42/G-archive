<?php
namespace App\Model\Services;


use App\Libs\StaticGlobalSettings;
use App\Model\Game;
use App\Model\Structures\IGlobalSettings;
use Kdyby\Doctrine\EntityManager;

class Games extends BaseService
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(Game::class));
	}
}
