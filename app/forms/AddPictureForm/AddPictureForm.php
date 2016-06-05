<?php

namespace App\Forms;

use App\Model\Services\Games;
use Nette\Application\UI as UI;
use Nette\Forms\Form;

class AddPictureForm extends UI\Control{

	public $onSave = [];

	/** @var Games */
	private $games;

	private $file_limit;


	public function __construct(Games $games)
	{
		parent::__construct();
		$this->games = $games;
		$this->file_limit = 2;
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

		$form->addSelect('id_game', 'Výběr hry', $this->games->findPairs('name'))->setPrompt('Nespojovat s žádnou hrou.');

		$form->addMultiUpload('pictures');

		$form->addSubmit('save', 'Prilepit špageti');

		$form->onSuccess[] = $this->processForm;

		return $form;
	}


	public function processForm(Form $form, $values)
	{

		$this->onSave($this);
	}
}

interface IAddPictureFormFactory
{
	/** @return AddPictureForm */
	function create();
}