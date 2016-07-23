<?php
namespace App\Model\Services;


use App\Model\Picture;
use Kdyby\Doctrine\EntityManager;

class Pictures extends BaseService
{

	/** @var GamePictures */
	public $gamePictures;

	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(Picture::class));
		$this->gamePictures = new GamePictures($em);
	}

	public function findLoose()
	{
		$query = $this->em->createQuery("SELECT picture FROM App\Model\Picture picture WHERE picture INSTANCE OF App\Model\Picture");
		$pictures = $query->getResult();

		return $pictures;
	}
}
