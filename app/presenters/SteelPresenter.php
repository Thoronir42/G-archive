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
	/** @var Initialiser @inject  */
	public $initialiser;

	public function startup()
	{
		parent::startup();
		if(!$this->user->isLoggedIn()){
			$this->flashMessage("To access Steel, you must be Steel.");
			$this->redirect('Sign:in');
		}
		$this->template->title = "Steelasdf";
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
