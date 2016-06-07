<?php

namespace App\Presenters;

use Nette;
use App\Forms\ISignInFormFactory;


class SignPresenter extends BasePresenter
{
	/** @var ISignInFormFactory @inject */
	public $factory;

	public function actionDefault()
	{
		$this->redirect('in');
	}


	public function renderIn()
	{

	}

	public function actionOut()
	{
		$this->getUser()->logout(true);
		$this->redirect('Games:');


	}


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$presenter = $this;

		$form->onSave[] = function ($form) use ($presenter) {
			$presenter->redirect('Games:');
		};

		return $form;
	}

}
