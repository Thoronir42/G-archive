<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 25.06.2016
 * Time: 10:52
 */

namespace App\Model\Helpers;


use App\Model\Game;
use Nette\Object;

class GroupedGames extends Object
{
	/** @var NamedGameGroup[] */
	protected $groups;


	public function __construct($games)
	{
		$this->groups = $this->createGroups();
		/** @var Game $game */
		foreach ($games as $game){
			/** @var NamedGameGroup $group */
			foreach ($this->groups as $group){
				if($group->titleBelongs($game->name)){
					$group->addGame($game);
					break;
				}
			}
		}
	}

	private function createGroups()
	{
		$groups = [];

		$groups[] = new NamedGameGroup('0', '9');
		$groups[] = new NamedGameGroup('A', 'F');
		$groups[] = new NamedGameGroup('G', 'L');
		$groups[] = new NamedGameGroup('M', 'R');
		$groups[] = new NamedGameGroup('S', 'Z');
		$groups[] = new NamedGameGroup();


		return $groups;
	}

	/**
	 * @return NamedGameGroup[]
	 */
	public function getGroups()
	{
		return $this->groups;
	}


}