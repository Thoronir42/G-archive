<?php

namespace App\Presenters;

use App\Forms\IEditGameFormFactory;
use App\Libs\Initialiser;
use App\Model\Game;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use Nette;
use App\Model;


class SteelPresenter extends BasePresenter
{
	const GAME_COLS = 4; // Musí být dělitel 12

	/** @var Games @inject  */
	public $games;

	/** @var Pictures @inject  */
	public $pictures;

	/** @var IEditGameFormFactory @inject  */
	public $editGameFormFactory;

	/** @var Initialiser @inject  */
	public $initialiser;

	public function startup()
	{
		parent::startup();

		if(false && !$this->user->isLoggedIn()){
			$this->flashMessage("To access Steel, you must be Steel.");
			$this->redirect('Games:');
		}
	}

	public function renderDefault()
	{

	}

	public function actionInitialise(){
		$this->initialiser->initialise();

		foreach ($this->initialiser->messages as $message){
			$this->flashMessage($message);
		}

		$this->flashMessage("Databáze inicializována (pravděpodobně)");
		$this->redirect('default');
	}

}
