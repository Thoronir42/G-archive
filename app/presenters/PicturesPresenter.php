<?php

namespace App\Presenters;
use App\Forms\IAddPictureFormFactory;
use App\Forms\IEditGameFormFactory;
use App\Libs\ImageManager;
use App\Model\Game;
use App\Model\GamePicture;
use App\Model\Picture;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


/**
 * Description of Worker
 *
 * @author Stepan
 */
class PicturesPresenter extends BasePresenter{

	/** @var IAddPictureFormFactory @inject  */
	public $addPictureFormFactory;

	/** @var Games @inject */
	public $games;

	/** @var Pictures @inject  */
	public $pictures;

	/** @var ImageManager @inject */
	public $imageManager;

	public function startup()
	{
		parent::startup();
		$this->steelCheck();

	}

	public function handleDelete($id){
		/** @var Picture $picture */
		$picture = $this->pictures->find($id);
		try{
			$this->pictures->delete($picture);
		} catch (ForeignKeyConstraintViolationException $ex){
			$this->flashMessage("Nelze odstranit primární obrázek hry");
			$this->redirect('default');
		}

		$this->imageManager->delete($picture->path);
	}

	public function handleSelect($id){

		/** @var GamePicture $picture */
		$picture = $this->pictures->find($id);

		if(!$picture){
			$this->flashMessage("Obrázek $id nebyl nalezen");
			$this->redirect('default');
		}

		$game = $picture->game;
		$game->primary_picture = $picture;
		
		$this->games->save($game);

		$this->flashMessage("Primární obrázek hry $game->name byl nasaven.");
		$this->redirect('default');
	}

	public function renderDefault() {
		$this->template->title = "Zátkovy těstoviny";
		$this->template->games = $this->games->findAll();

		$this->template->loose_pictures = $this->pictures->findLoose();
	}

	public function createComponentAddPictureForm()
	{
		$form = $this->addPictureFormFactory->create();

		$form->onSave[] = function ($form, $pictures, Game $game){
			/** @var Picture $picture */
			foreach ($pictures as $picture){
				$this->pictures->save($picture, false);
			}
			$this->pictures->flush();
			
			$this->flashMessage("Bylo přidáno " . sizeof($pictures) . " obrázků ke hře $game->name");
			$this->redirect('default');
		};

		return $form;
	}



}
