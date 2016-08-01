<?php

namespace App\Presenters;

use App\Controls\NavigationMenu\INavigationMenuFactory;
use App\Controls\IStateViewFactory;
use App\Model\Structures\IGlobalSettings;
use Core\Traits\LinkMaking;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	use LinkMaking;

	/** @var  INavigationMenuFactory @inject */
	public $navigationMenuFactory;
	
	/** @var IStateViewFactory @inject */
	public $stateViewFactory;

	/** @var IGlobalSettings @inject */
	public $global_settings;

	public function startup()
	{
		parent::startup();

		$this->template->max_rating = $this->global_settings->getMaxRating();
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
		$menu->setBrandAction('Games:');

		$menu->addLink('Games:default', 'Hry');
		$menu->addLink('Pictures:', 'Obrázky');

		if($this->user->isLoggedIn()){
			$manageItem = $menu->addLink('Steel:default', 'Správa');
			$manageItem->addLink('Games:add', 'Zadat novou hru');
			$manageItem->addSeparator();
			$manageItem->addLink('Platforms:', 'Platformy');
			$manageItem->addLink('States:', 'Stavy');
			$manageItem->addLink('Tags:', 'Tagy');
		}

		return $menu;
	}

	public function createComponentState()
	{
		return $this->stateViewFactory->create();
	}
}
