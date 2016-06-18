<?php

namespace App\Forms;

use App\Model\Platform;
use Nette\Application\UI as UI;


use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;
use Nette\Security\User;

class EditPlatformForm extends UI\Control
{

	public $onSave = [];

	/** @var FormFactory */
	private $factory;

	/** @var Platform */
	private $platform;


	public function __construct(FormFactory $factory)
	{
		parent::__construct();
		$this->factory = $factory;
		$this->platform = new Platform();
	}
	
	public function setEntity(Platform $platform){
		$this->platform = $platform;

		/** @var Form $form */
		$form = $this['form'];

		$form->setDefaults($platform->toArray());

		/** @var SubmitButton $submit */
		$submit = $form['save'];
		$submit->caption = 'Upravit';

	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/editPlatformForm.latte');
		$this->template->render();
	}

	public function createComponentForm()
	{
		$form = $this->factory->create();

		$form->addText('name', 'Název')->setMaxLength(420)->setRequired();
		$form->addText('count', 'Počet')->setDefaultValue(1);

		$form->addUpload('picture', 'Foto');

		$form->addSubmit('send', 'Vložit');

		$form->onSuccess[] = $this->processForm;
		return $form;
	}

	public function processForm(Form $form, $values)
	{
		$this->onSave($this);
	}
}

interface IEditPlatformFormFactory
{
	/** @return EditPlatformForm */
	function create();
}