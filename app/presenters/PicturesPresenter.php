<?php

namespace App\Presenters;
use App\Forms\IAddPictureFormFactory;
use App\Forms\IEditGameFormFactory;
use App\Model\Services\Games;


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

	public function renderDefault() {
		$this->template->subtitle = "loljk... maby latr. Defintly";
		$this->template->games = $this->games->findAll();
	}

	public function doPridejObrazky() {
		$id_game = $this->getParam("id_game", INPUT_POST);
		$files = \model\FileManager::reArrayFiles($_FILES['picture']);
		$result = \model\ImageManager::putMany($files);
		$images = [];
		foreach($result['successes'] as $s){
			$images[] = ['id_game' => $id_game, 'picture_path' => $s['path']];
		}
		$this->pdoWrapper->insertImage($images, true);
		$this->redirect("obrazky");
	}

	public function createComponentAddPictureForm()
	{
		$form = $this->addPictureFormFactory->create();


		return $form;
	}

}
