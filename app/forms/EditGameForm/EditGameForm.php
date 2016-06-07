<?php

namespace App\Forms;

use App\Libs\GASettings;
use App\Libs\ImageManager;
use App\Model\Game;
use App\Model\Picture;
use App\Model\Services\States;
use Components\EntityForm;
use Nette\Application\UI as UI;
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

	/** @var ImageManager */
	private $imageManager;


	public function __construct(ImageManager $imageManager, States $states, GASettings $game_settings)
	{
		parent::__construct();

		$this->imageManager = $imageManager;
		$this->states = $states;
		$this->game_settings = $game_settings;
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
		/** @var EntityForm $form */
		$form = $this['form'];

		$form->bindEntity($game);

		/** @var SelectBox $select */
		$select = $form['picture_select'];

		$picture_pairs = [];
		foreach ($game->pictures as $picture) {
			$picture_pairs[$picture->getId()] = $picture->path;
		}

		$select->setDisabled(empty($picture_pairs));
		$select->setItems($picture_pairs);
		$select->setValue($game->primary_picture->getId());

		/** @var RadioList $radios */
		$radios = $form['picture_src'];
		$radios->setDisabled(false);

		/** @var SubmitButton $submit */
		$submit = $form['save'];

		$submit->setValue('Upravit');

	}

	public function createComponentForm()
	{
		$states = $this->states->findPairs('label');

		$form = new EntityForm;

		$form->addText('name', 'Název');
		$form->addUpload('picture', 'Titulní obrázek');

		$form->addRadioList('picture_src', '',
			[self::$PICTURE_SRC_UPLOAD => '', self::$PICTURE_SRC_SELECT => ''])
		->setDisabled([self::$PICTURE_SRC_SELECT]);

		$form->addSelect('picture_select', [])->setPrompt("Výběr primárního obrázku")->setDisabled();

		$form->addSelect('cartridge_state', 'Cartridge', $states);
		$form->addSelect('packing_state', 'Balení', $states);
		$form->addSelect('manual_state', 'Manuál', $states);

		$form->addText('completion', 'Dokončení');
		$form->addText('affection', 'Obliba');

		$form->addSubmit('save', 'Přidat');

		$form->onSuccess[] = $this->processForm;

		$form->bindEntity(new Game());
		$form->setDefaults(['picture_src' => 'upload', 'affection' => 10]);

		return $form;
	}


	public function processForm(EntityForm $form, $values)
	{
		/** @var Game $game */
		$game = $form->getEntity();

		/** @var FileUpload $picture */
		$picture = $values['picture'];
		unset($values['picture']);

		$picture_src = $values['picture_src'];
		unset($values['picture_src']);

		if($picture_src == self::$PICTURE_SRC_SELECT){
			$values['primary_picture'] = $values['pic_select'];
		}

		$state_fields = ['cartridge_state', 'packing_state', 'manual_state'];
		foreach ($state_fields as $field){
			$values[$field] = $this->states->find($values[$field]);
		}

		foreach ($values as $field => $value) {
			$game->$field = $value;
		}

		$path = $this->imageManager->put($picture);
		if($path){
			$out_picture = new Picture;
			$out_picture->game = $game;
			$out_picture->path = $path;
		}

		$this->onSave($form, $game, $out_picture);
	}
}

interface IEditGameFormFactory
{
	/** @return EditGameForm */
	function create();
}