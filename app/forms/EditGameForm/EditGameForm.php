<?php

namespace App\Forms;

use App\Libs\GASettings;
use App\Model\Game;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use App\Model\Services\States;
use Components\EntityForm;
use Nette\Application\UI as UI;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Form;

class EditGameForm extends UI\Control
{

	public $onSave = [];

	/** @var Games */
	private $games;

	/** @var Pictures  */
	private $pictures;


	/** @var  GASettings */
	private $game_settings;

	/** @var States */
	private $states;


	public function __construct(Games $games, Pictures $pictures, States $states, GASettings $game_settings)
	{
		parent::__construct();

		$this->games = $games;
		$this->game_settings = $game_settings;
		$this->pictures = $pictures;
		$this->states = $states;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/editGameForm.latte');

		$this->template->range_accuracy = $this->game_settings->getCompletionRange();
		$this->template->max_affection = $this->game_settings->getMaxRating();

		$this->template->render();
	}

	public function setGame(Game $game, $pictures){
		/** @var EntityForm $form */
		$form = $this['form'];
		
		$form->bindEntity($game);

		/** @var SelectBox $select */
		$select = $form['pictyre_src'];

		$select->setItems($pictures);
		$select->setValue($game->primary_picture->getId());

	}

	public function createComponentForm()
	{
		$states = $this->states->findPairs('label');

		$form = new EntityForm;

		$form->addText('name', 'Název');
		$form->addUpload('picture', 'Titulní obrázek');

		$form->addRadioList('picture_src', '', ['upload' => '', 'select' => '']);

		$form->addSelect('img_select', [])->setPrompt("Zde půjde vybírat primární obrázek hry")->setDisabled(true);

		$form->addSelect('cartridge_state', 'Cartridge', $states);
		$form->addSelect('packing_state', 'Balení', $states);
		$form->addSelect('manual_state', 'Manuál', $states);
		
		$form->addText('completion', 'Dokončení');
		$form->addText('affection', 'Obliba');
		
		$form->addHidden('id');

		$form->addSubmit('save', 'Přidat');

		$form->onSuccess[] = $this->processForm;

		$form->bindEntity(new Game());
		$form->setDefaults(['picture_src' => 'upload']);

		return $form;
	}


	public function processForm(EntityForm $form, $values)
	{
		dump($form->getEntity());
		dump($values);exit;
		$this->onSave($this);
	}
}

interface IEditGameFormFactory
{
	/** @return EditGameForm */
	function create();
}