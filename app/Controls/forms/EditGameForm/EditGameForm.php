<?php

namespace App\Forms;

use App\Libs\GASettings;
use App\Libs\ImageManager;
use App\Model\Game;
use App\Model\GamePicture;
use App\Model\Services\Platforms;
use App\Model\Services\States;
use App\Model\Services\Tags;
use App\Model\Tag;
use Nette\Application\UI as UI;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\FileUpload;

class EditGameForm extends UI\Control
{

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
	/**
	 * @var Tags
	 */
	private $tags;


	public function __construct(ImageManager $imageManager, States $states, Platforms $platforms,
								GASettings $game_settings, Tags $tags)
	{
		parent::__construct();

		$imageManager->setMode(ImageManager::MODE_GAME);
		$this->game = new Game();

		$this->game_settings = $game_settings;
		$this->imageManager = $imageManager;
		$this->platforms = $platforms;
		$this->states = $states;
		$this->tags = $tags;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/editGameForm.latte');
		$this->template->max_affection = $this->game_settings->getMaxRating();

		$this->template->render();
	}

	/**
	 * @param Game $game
	 */
	public function setGame(Game $game)
	{
		$this->game = $game;
		/** @var Form $form */
		$form = $this['form'];

		$tags = [];

		/** @var Tag $completion_tag */
		foreach ($game->completion_tags as $completion_tag){
			$tags[] = $completion_tag->title;
		}

		$defaults = $game->toArray();
		$defaults['completion_tags'] = $tags;


		$form->setDefaults($defaults);


		/** @var SubmitButton $submit */
		$submit = $form['save'];
		$submit->caption = "Uložit změny";

	}

	public function createComponentForm()
	{
		$states = $this->states->findPairs([], 'label');
		$disabled_states = $this->states->findPairs(['deleted' => true], 'label');
		$platforms = $this->platforms->findPairs('title');

		$tags = $this->tags->getAllTitles();

		$form = new Form;

		$form->addText('name', 'Název');
		$form->addUpload('picture', 'Titulní obrázek');

		$form->addSelect('platform', 'Platforma', $platforms)->setPrompt('Bez výběru');

		$form->addSelect('cartridge_state', 'Cartridge', $states)
			->setDisabled($disabled_states);
		$form->addSelect('packing_state', 'Balení', $states)
			->setDisabled($disabled_states);
		$form->addSelect('manual_state', 'Manuál', $states)
			->setDisabled($disabled_states);

		$form->addMultiSelect('completion_tags', 'Dokončení', $tags)
			->getControlPrototype()->class[] = 'tags';
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

		$tag_titles = isset($_POST['completion_tags']) ? $_POST['completion_tags'] : [];
		// $tag_titles = $values['completion_tags']; // empty

		$tags = $this->tags->getTags($tag_titles);
		
		unset($values['completion_tags']);

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

		$this->onSave($form, $game, $out_picture, $tags);
	}
}

interface IEditGameFormFactory
{
	/** @return EditGameForm */
	function create();
}