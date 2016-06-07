<?php

namespace App\Presenters;

use App\Forms\EditGameForm;
use App\Forms\IEditGameFormFactory;
use App\Model\Game;
use App\Model\Picture;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use Components\EntityForm;
use Nette;
use App\Model;


class GamesPresenter extends BasePresenter
{
	const GAME_COLS = 4; // Musí být dělitel 12

	/** @var Games @inject  */
	public $games;

	/** @var Pictures @inject  */
	public $pictures;

	/** @var IEditGameFormFactory @inject  */
	public $editGameFormFactory;

	public function startup()
	{
		parent::startup();
		$action = $this->getAction();
		$allowed_actions = ['default'];
		if(!in_array($action, $allowed_actions) && !$this->user->isLoggedIn()){
			$this->flashMessage('Jsi opravdu Steel, že chceš upravovat hry?');
			$this->redirect('default');
		}
	}

	public function renderDefault()
	{
		$games = $this->games->findAll();
		$this->template->games = $games;
		$this->template->column_count = self::GAME_COLS;
	}



	public function actionEdit($id) {
		$game = $this->games->find($id);

		if (!$game) {
			$this->flashMessage("Hra č. $id k editaci nebyla nalezena.");
			$this->redirect("default");
		}

		/** @var EditGameForm $form */
		$form = $this['editGameForm'];
		$form->setGame($game);

		$this->template->subtitle = "Úprava detailů hry $game->name";
	}

	public function renderAdd(){
		$this->setView('edit');

		$this->template->subtitle = "Vložení nové hry";
	}

	public function actionDelete($id){
		$game = $this->games->find($id);
		if($game){
			$this->games->delete($game);
			$this->flashMessage("Hra $game->name byla odstraněna");
		} else {
			$this->flashMessage("Pro smazání udej i ID, plz.");
		}

		$this->redirect('default');
	}

	public function createComponentEditGameForm()
	{
		$form = $this->editGameFormFactory->create();

		$form->onSave[] = function (EntityForm $form, Game $game, $picture = null){
			if($picture){
				if(!($picture instanceof Picture)){
					$picture = $this->pictures->find($picture);
				}
				$game->primary_picture = $picture;
				$this->pictures->save($picture, false);
			}

			$this->games->update($game, $game->toArray());
			$this->flashMessage("Hra $game->name byla úspěšně přidána.");
			$this->redirect('default');
		};

		return $form;
	}
}
