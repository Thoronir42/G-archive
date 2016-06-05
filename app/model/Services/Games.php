<?php
namespace App\Model\Services;


use App\Libs\GASettings;
use App\Model\BaseEntity;
use App\Model\Game;
use Kdyby\Doctrine\EntityManager;

class Games extends BaseService
{
	/** @return GASettings */
	public static function getSettings(){
		return GASettings::instance();
	}


	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(Game::class));
	}
}