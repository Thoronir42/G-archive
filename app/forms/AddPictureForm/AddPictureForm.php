<?php

namespace App\Forms;

use App\Libs\ImageManager;
use App\Model\Picture;
use App\Model\Services\Games;
use Nette\Application\UI as UI;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

class AddPictureForm extends UI\Control{

	public $onSave = [];

	/** @var Games */
	private $games;

	private $file_limit;

	/** @var ImageManager */
	private $imageManager;


	public function __construct(Games $games, ImageManager $imageManager)
	{
		parent::__construct();
		$this->file_limit = 2;

		$this->games = $games;
		$this->imageManager = $imageManager;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/addPictureForm.latte');
		$this->template->file_limit = $this->file_limit;
		$this->template->render();
	}

	public function createComponentForm()
	{
		$form = new Form;

		$form->addSelect('id_game', 'Výběr hry', $this->games->findPairs('name'))->setPrompt('Spojit s hrou...')->setDisabled(['']);

		$form->addMultiUpload('pictures');

		$form->addSubmit('save', 'Prilepit špageti');

		$form->onSuccess[] = $this->processForm;

		return $form;
	}


	public function processForm(Form $form, $values)
	{
		$game = $this->games->find($values['id_game']);
		if(!$game){
			$form['id_game']->addError("Hra nebyla nalezena");
			return;
		}
		$pictures = [];


		/** @var FileUpload $upload */
		foreach ($values['pictures'] as $upload){
			$path = $this->imageManager->put($upload);
			if(!$path){
				$form->addError("Obrázek $upload->name se nepodařilo nahrát");
				continue;
			}
			$picture = new Picture();
			$picture->path = $path;
			$picture->game = $game;

			$pictures[] = $picture;
		}

		$this->onSave($form, $pictures, $game);
	}
}

interface IAddPictureFormFactory
{
	/** @return AddPictureForm */
	function create();
}