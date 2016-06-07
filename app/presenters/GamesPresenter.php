<?php

namespace App\Presenters;

use App\Forms\EditGameForm;
use App\Forms\IEditGameFormFactory;
use App\Libs\ImageManager;
use App\Model\Game;
use App\Model\Picture;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use Components\EntityForm;
use Nette;
use App\Model;
use Nette\Http\FileUpload;


class GamesPresenter extends BasePresenter
{
	const GAME_COLS = 4; // Musí být dělitel 12

	/** @var Games @inject  */
	public $games;

	/** @var Pictures @inject  */
	public $pictures;

	/** @var IEditGameFormFactory @inject  */
	public $editGameFormFactory;

	public function renderDefault()
	{
		$games = $this->games->findAll();
		$this->template->games = $games;
		$this->template->column_count = self::GAME_COLS;
	}



	public function renderEdit($id = null) {
		if(!$this->user->isLoggedIn()){
			$this->flashMessage('Jsi opravdu Steel, že chceš upravovat hry?');
			$this->redirect('default');
		}
		if($id === null){
			$this->flashMessage("Pro editaci udej i ID, plz.");
			$this->redirect('default');
		}

		/** @var EditGameForm $form */
		$form = $this['editGameForm'];

		if($id){
			$game = $this->games->find($id);

			if (!$game) {
				$this->flashMessage("Hra č. $id k editaci nebyla nalezena.");
				$this->redirect("default");
			}

			$subtitle = "Úprava detailů hry $game->name";

			$form->setGame($game);
		} else {
			$subtitle = "Vložení nové hry";
		}

		$this->template->subtitle = $subtitle;
	}

	public function renderAdd(){
		$this->renderEdit(0);
		$this->setView('edit');
	}

	public function actionDelete($id){
		$this->redirect('default');
	}

	public function doVloz() {
		$p = ImageManager::put("picture");
		if (!$p['result']) {
			echo $p['message'];
			echo "<a href=\"" . $this->URLgen->url(['action' => 'vypis']) . "\">Pokračovat na výpis</a>";
			die;
		}
		$picture_path = $p['path'];

		$pic = ["picture_path" => $picture_path,
			"description" => filter_input(INPUT_POST, "picture_description"),
			"id_game" => NULL];
		if (!$pic['picture_path']) {
			echo "Chyba při nahrávání obrázku";
			$this->redirect("vlozeni");
		}
		$id_picture = $this->pdoWrapper->insertImage($pic);
		$game = $this->prepareGameAsArray($id_picture);
		$id_game = $this->pdoWrapper->insertGame($game);
		$this->pdoWrapper->linkPicture($id_picture, $id_game);

		$this->redirect("vypis");
	}

	public function createComponentEditGameForm()
	{
		$form = $this->editGameFormFactory->create();

		$form->onSave[] = function (EntityForm $form, Game $game, Picture $picture){
			if($picture){
				$game->primary_picture = $picture;
			}
			$this->games->save($game, false);
			$this->pictures->save($picture, false);
			$this->games->flush();

			$this->flashMessage("Hra $game->name byla úspěšně přidána.");
			$this->redirect('default');
		};

		return $form;
	}

	public function doUprav() {
		$game = \model\db\Game::fromPost()->toArray();
		switch ($this->getParam('picture-src', INPUT_POST)) {
			case 'upload':
				$p = \model\ImageManager::put("picture");
				if (!$p['result']) {
					echo "Chyba při nahrávání obrázku";
					break;
				}
				$pic = ["picture_path" => $p['path'],
					"id_game" => $game['id_game']];
				$game['picture'] = $this->pdoWrapper->insertImage($pic);
				break;
			case 'select':
				$game['picture'] = $this->getParam('imgSelect', INPUT_POST);
				break;
			default:
				$game['picture'] = null;
				break;
		}
		$game['completion'] = $game['completion'] * 1.0 / $this->gameParams->getCompletionRange();
		echo $this->pdoWrapper->editGame($game);
		$this->redirect("vypis");
	}

}
