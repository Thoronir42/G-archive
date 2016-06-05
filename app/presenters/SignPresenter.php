<?php

namespace App\Presenters;

use Nette;
use App\Forms\SignFormFactory;


class SignPresenter extends BasePresenter
{
	/** @var SignFormFactory @inject */
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
		$this->getUser()->logout();

	}


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Games:');
		};
		return $form;
	}

}
