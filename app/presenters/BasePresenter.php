<?php

namespace App\Presenters;

use App\Controls\INavigationMenuFactory;
use App\Libs\GASettings;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var  INavigationMenuFactory @inject */
	public $navigationMenuFactory;

	/** @var GASettings @inject */
	public $gameParams;

	public function startup()
	{
		parent::startup();

		$this->template->max_affection = $this->gameParams->getMaxRating();
		$this->template->image_dir = __DIR__ . '/../../www/images/games/';

		$this->template->title = '';

	}

	protected function steelCheck($message = 'Pro vstup do této části musíš být přihlášen.', $allowedActions = ['default']){
		$action = $this->getAction();
		if(in_array($action, $allowedActions) || $this->user->isLoggedIn()){
			return;
		}

		$this->flashMessage($message);
		if(in_array('default', $allowedActions)){
			$this->redirect('default');
		} else {
			$this->redirect('Games:');
		}
	}

	public function createComponentMenu()
	{
		$menu = $this->navigationMenuFactory->create();
		$menu->setTitle('G archive');

		$menu->addItem('Games:default', 'Hry');
		$menu->addItem('Pictures:', 'Obrázky');

		if($this->user->isLoggedIn()){
			$manageItem = $menu->addItem('default', 'Správa');
			$manageItem->addItem('Games:add', 'Zadat novou hru');
			$manageItem->addSeparator();
			$manageItem->addItem('Platforms:', 'Platformy');
			$manageItem->addItem('States:', 'Stavy');
		}

		return $menu;
	}
}
