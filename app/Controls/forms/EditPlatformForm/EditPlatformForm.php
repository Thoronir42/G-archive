<?php

namespace App\Forms;

use App\Libs\ImageManager;
use App\Model\Platform;
use App\Model\PlatformPicture;
use Nette\Application\UI as UI;


use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\FileUpload;
use Nette\Security\User;

class EditPlatformForm extends UI\Control
{

	public $onSave = [];

	/** @var FormFactory */
	private $factory;

	/** @var Platform */
	private $platform;
	/** @var ImageManager */
	private $imageManager;


	public function __construct(FormFactory $factory, ImageManager $imageManager)
	{
		parent::__construct();
		$this->factory = $factory;

		$imageManager->setMode(ImageManager::MODE_PLATFORM);
		$this->imageManager = $imageManager;

		$this->platform = new Platform();
	}

	public function setEntity(Platform $platform)
	{
		$this->platform = $platform;

		/** @var Form $form */
		$form = $this['form'];

		$defaults = $platform->toArray();
		$form->setDefaults($defaults);

		/** @var SubmitButton $submit */
		$submit = $form['save'];
		$submit->caption = 'Uložit úpravy';

	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/editPlatformForm.latte');
		$this->template->render();
	}

	public function createComponentForm()
	{
		$form = $this->factory->create();

		$form->addText('title', 'Název')->setMaxLength(420)->setRequired();
		$form->addText('count', 'Počet')->setDefaultValue(1);

		$form->addUpload('picture', 'Foto');

		$form->addSubmit('save', 'Vložit');

		$form->onSuccess[] = $this->processForm;
		return $form;
	}

	public function processForm(Form $form, $values)
	{
		$platform = $this->platform;

		/** @var FileUpload $upload */
		$upload = $values['picture'];
		unset($values['picture']);

		$picture = $this->createPicture($upload);
		if($upload->name && $upload->error && !$picture){
			foreach ($this->imageManager->getErrors() as $error){
				$form['picture']->addError($error);
			}
			return;
		}

		foreach ($values as $field => $value){
			$platform->$field = $value;
		}

		$this->onSave($form, $platform, $picture);
	}

	private function createPicture(FileUpload $upload)
	{
		$path = $this->imageManager->put($upload);
		if ($path) {
			$picture = new PlatformPicture();
			$picture->path = $path;
			$picture->platform = $this->platform;
			return $picture;
		}

		return null;
	}
}

interface IEditPlatformFormFactory
{
	/** @return EditPlatformForm */
	function create();
}