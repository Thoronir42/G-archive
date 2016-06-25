<?php

namespace App\Presenters;

use App\Controls\GameView;
use App\Controls\PlatformView;
use App\Forms\EditGameForm;
use App\Forms\IEditGameFormFactory;
use App\Model\Game;
use App\Model\GamePicture;
use App\Model\Picture;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use App\Model\Services\Platforms;
use App\Model\Tag;
use Components\EntityForm;
use Nette;
use App\Model;
use Nette\Application\UI\Form;


class GamesPresenter extends BasePresenter
{
	/** @var Games @inject  */
	public $games;

	/** @var Platforms @inject */
	public $platforms;

	/** @var Pictures @inject  */
	public $pictures;

	/** @var IEditGameFormFactory @inject  */
	public $editGameFormFactory;

	public function startup()
	{
		parent::startup();
		$this->steelCheck("Pro správu her musíš být přihlášen.");
	}

	public function renderDefault()
	{
		$platforms = $this->platforms->findAll();

		$this->template->title  = "Vcesko";
		$this->template->platforms = $platforms;

		$this->template->unassignedGames = $this->games->findBy(['platform' => null]);
	}



	public function actionEdit($id) {
		/** @var Game $game */
		$game = $this->games->find($id);

		if (!$game) {
			$this->flashMessage("Hra č. $id k editaci nebyla nalezena.");
			$this->redirect("default");
		}

		/** @var EditGameForm $form */
		$form = $this['editGameForm'];
		$form->setGame($game);

		$this->template->title = "Úprava detailů hry $game->name";
	}

	public function renderAdd(){
		$this->setView('edit');

		$this->template->title = "Vložení nové hry";
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

	public function createComponentGame()
	{
		return new GameView();
	}

	public function createComponentPlatform()
	{
		return new PlatformView();
	}

	public function createComponentEditGameForm()
	{
		$form = $this->editGameFormFactory->create();

		$form->onSave[] = function (Form $form, Game $game, GamePicture $picture = null, $tags = []){
			if($picture){
				$game->primary_picture = $picture;
				$game->pictures->add($picture);

				$this->pictures->save($picture, false);
			}


			$game->completion_tags->clear();

			/** @var Tag $tag */
			foreach ($tags as $tag){
				$game->completion_tags->add($tag);
			}

			$this->games->save($game);
			$this->flashMessage("Hra $game->name byla úspěšně přidána.");
			$this->redirect('default');
		};

		return $form;
	}
}
