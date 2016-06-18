<?php

namespace App\Forms;

use Nette\Application\UI as UI;


use Nette\Application\UI\Form;
use Nette\Security\User;

class SignInForm extends UI\Control
{

	public $onSave = [];

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		parent::__construct();
		$this->factory = $factory;
		$this->user = $user;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/signInForm.latte');
		$this->template->render();
	}

	public function createComponentForm()
	{
		$form = $this->factory->create();
		$form->addText('username', 'Steel?')
			->setRequired('Steel steel steel steel Steel:');

		$form->addPassword('password', 'Steel?#|@')
			->setRequired('Steel steel.');

		$form->addCheckbox('remember', 'Steeeeeeeeeeeel');

		$form->addSubmit('send', 'Steel');

		$form->onSuccess[] = $this->processForm;
		return $form;
	}


	public function processForm(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->user->login($values->username, $values->password);
			$this->setErrorCount(0);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($this->errorMessage());
		}
		$this->onSave($this);
	}

	private function errorMessage(){

		$error_count = $this->getErrorCount();
		$error_count++;
		$this->setErrorCount($error_count);

		$error = 'Tohle se nepovedlo. ';
		if($error_count > 1){
			$error .= "x$error_count";
		}
		return $error;
	}

	private function getErrorCount(){
		return isset($_SESSION['login_error_count']) ? $_SESSION['login_error_count'] : 0;
	}

	private function setErrorCount($n){
		if(!$n){
			unset($_SESSION['login_error_count']);
			return;
		}
		$_SESSION['login_error_count'] = $n;
	}
}

interface ISignInFormFactory
{
	/** @return SignInForm */
	function create();
}