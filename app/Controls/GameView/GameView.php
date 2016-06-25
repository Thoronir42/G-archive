<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 23.06.2016
 * Time: 22:30
 */

namespace App\Controls;

use App\Model\Game;
use Nette\Application\UI;

class GameView extends UI\Control
{
	public function __construct()
	{
		parent::__construct();
	}

	public function renderLarge(){
		$this->template->game = $this->game;
	}

	public function renderMini(Game $game){
		$this->template->game = $game;

		$this->template->setFile(__DIR__ . '/gameMini.latte');
		$this->template->render();
	}
}