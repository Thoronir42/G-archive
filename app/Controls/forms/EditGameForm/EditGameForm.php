<?php

namespace App\Forms;

use App\Libs\GASettings;
use App\Libs\ImageManager;
use App\Model\Game;
use App\Model\GamePicture;
use App\Model\Picture;
use App\Model\Services\Games;
use App\Model\Services\Platforms;
use App\Model\Services\States;
use Components\EntityForm;
use Nette\Application\UI as UI;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\FileUpload;

class EditGameForm extends UI\Control
{

	private static $PICTURE_SRC_UPLOAD = 'upload';
	private static $PICTURE_SRC_SELECT = 'select';


	public $onSave = [];

	/** @var  GASettings */
	private $game_settings;

	/** @var States */
	private $states;
	/** @var Platforms */
	private $platforms;

	/** @var ImageManager */
	private $imageManager;

	/** @var Game */
	private $game;


	public function __construct(ImageManager $imageManager, States $states, Platforms $platforms, GASettings $game_settings)
	{
		parent::__construct();

		$imageManager->setMode(ImageManager::MODE_GAME);

		$this->imageManager = $imageManager;
		$this->states = $states;
		$this->game_settings = $game_settings;

		$this->game = new Game();
		$this->platforms = $platforms;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/editGameForm.latte');

		$this->template->range_accuracy = $this->game_settings->getCompletionRange();
		$this->template->max_affection = $this->game_settings->getMaxRating();

		$this->template->render();
	}

	/**
	 * @param Game $game
	 */
	public function setGame(Game $game)
	{
		$this->game = $game;
		/** @var EntityForm $form */
		$form = $this['form'];

		$form->setDefaults($game->toArray());


		/** @var SubmitButton $submit */
		$submit = $form['save'];
		$submit->caption = "Uložit změny";

		$defaults = [
			'completion' => $game->completion * $this->game_settings->getCompletionRange(),
		];
		if ($game->primary_picture) {
			$defaults['picture_select'] = $game->primary_picture->getId();
		}
		if (!empty($picture_pairs)) {
			$defaults['picture_src'] = self::$PICTURE_SRC_SELECT;
		}
		$form->setDefaults($defaults);

	}

	public function createComponentForm()
	{
		$states = $this->states->findPairs('label');
		$platforms = $this->platforms->findPairs('title');

		$form = new Form;

		$form->addText('name', 'Název');
		$form->addUpload('picture', 'Titulní obrázek');

		$form->addSelect('platform', 'Platforma', $platforms)->setPrompt('Bez výběru');

		$form->addSelect('cartridge_state', 'Cartridge', $states);
		$form->addSelect('packing_state', 'Balení', $states);
		$form->addSelect('manual_state', 'Manuál', $states);

		$form->addText('completion', 'Dokončení');
		$form->addText('affection', 'Obliba');

		$form->addSubmit('save', 'Přidat');

		$form->onSuccess[] = $this->processForm;

		$form->setDefaults(['affection' => $this->game_settings->getMaxRating() / 2]);

		return $form;
	}


	public function processForm(Form $form, $values)
	{
		/** @var Game $game */
		$game = $this->game;

		/** @var FileUpload $picture */
		$picture = $values['picture'];
		unset($values['picture']);

		$state_fields = ['cartridge_state', 'packing_state', 'manual_state'];
		foreach ($state_fields as $field) {
			$values[$field] = $this->states->find($values[$field]);
		}

		$values['platform'] = $this->platforms->find($values['platform']);

		$values['completion'] /= $this->game_settings->getCompletionRange();

		foreach ($values as $field => $value) {
			$game->$field = $value;
		}

		$path = $this->imageManager->put($picture);
		if ($path) {
			$out_picture = new GamePicture();
			$out_picture->path = $path;
			$out_picture->game = $game;
		} else {
			$out_picture = null;
		}

		$this->onSave($form, $game, $out_picture);
	}
}

interface IEditGameFormFactory
{
	/** @return EditGameForm */
	function create();
}