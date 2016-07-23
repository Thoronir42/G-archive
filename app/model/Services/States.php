<?php
namespace App\Model\Services;


use App\Model\State;
use Kdyby\Doctrine\EntityManager;

class States extends BaseService
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(State::class));
	}

	public function delete($state)
	{
		if($state instanceof State){
			$state->deleted = true;
			$this->save($state);
		} else {
			parent::delete($state);
		}
	}
	public function undelete(State $state){
		$state->deleted = false;
		$this->save($state);
	}
}
