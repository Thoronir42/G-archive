<?php

namespace App\Presenters;

use App\Controls\GameView;
use App\Controls\IGameViewFactory;
use App\Controls\IPlatformViewFactory;
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
	/** @var Games @inject */
	public $games;
	/** @var Platforms @inject */
	public $platforms;
	/** @var Pictures @inject */
	public $pictures;

	/** @var IEditGameFormFactory @inject */
	public $editGameFormFactory;
	/** @var IGameViewFactory @inject  */
	public $game_view_factory;
	/** @var IPlatformViewFactory @inject */
	public $platform_view_factory;

	public function startup()
	{
		parent::startup();
		$this->steelCheck("Pro správu her musíš být přihlášen.");
	}

	public function renderDefault($bs = false)
	{
		$platforms = $this->platforms->findAll();


		/** @var PlatformView $platformComponent */
		$platformComponent = $this['platform'];
		$platformComponent->setListBootstrapTemplate($bs);

		$this->template->title = "Výpis her podle platforem";
		$this->template->platforms = $platforms;
		$this->template->unassignedGames = $unassigned = $this->games->findBy(['platform' => null]);

		$additional_nav_links = [];

		if($unassigned){
			$additional_nav_links[] = $this->createLink('this#unassigned', 'Hry bez platformy');
		}
		$this->template->additional_nav_links = $additional_nav_links;
	}


	public function actionEdit($id)
	{
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

	public function renderAdd()
	{
		$this->setView('edit');

		$this->template->title = "Vložení nové hry";
	}

	public function actionDelete($id)
	{
		$game = $this->games->find($id);
		if ($game) {
			$this->games->delete($game);
			$this->flashMessage("Hra $game->name byla odstraněna");
		} else {
			$this->flashMessage("Pro smazání udej i ID, plz.");
		}

		$this->redirect('default');
	}

	public function handleOpen($id)
	{
		$game = $this->games->find($id);
		if(!$game){
			$this->sendJson(['status' => 'error', 'message' => 'Game not found.']);
		}
		$this->template->platforms = [];
		$this->template->selected_game = $game;
		$this->redrawControl('gameModal');
	}

	public function createComponentGame()
	{
		return $this->game_view_factory->create();
	}

	public function createComponentGameModal()
	{
		return $this->game_view_factory->create(GameView::TYPE_MODAL);
	}

	public function createComponentPlatform()
	{
		return $this->platform_view_factory->create();
	}

	public function createComponentEditGameForm()
	{
		$form = $this->editGameFormFactory->create();

		$form->onSave[] = function (Form $form, Game $game, GamePicture $picture = null, $tags = []) {
			if ($picture) {
				$game->primary_picture = $picture;
				$game->pictures->add($picture);

				$this->pictures->save($picture, false);
			}


			$game->completion_tags->clear();

			/** @var Tag $tag */
			foreach ($tags as $tag) {
				$game->completion_tags->add($tag);
			}

			$this->games->save($game);
			$this->flashMessage("Hra $game->name byla úspěšně přidána.");
			$this->redirect('default');
		};

		return $form;
	}
}
