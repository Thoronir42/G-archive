<?php

namespace App\Presenters;

use App\Controls\INavigationMenuFactory;
use App\Controls\NavigationMenu;
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

		/** @var NavigationMenu $menu */
		$menu = $this['menu'];
		$menu->setTitle('G archive');

		$this->template->title = '';

	}

	protected function steelCheck($allowedActions = []){
		$action = $this->getAction();

		if($action == 'default'){
			return;
		}


		if(!in_array($action, $allowedActions) && !$this->user->isLoggedIn()){
			$this->flashMessage('Jsi opravdu Steel, že chceš provádět \'' . $action . '\'?');
			$this->redirect('default');
		}
	}

	public function createComponentMenu()
	{
		$menu = $this->navigationMenuFactory->create();

		$items = $this->buildMenu();

		foreach ($items as $item){
			$menu->addItem($item['code'], $item['title']);
		}

		return $menu;
	}

	private function buildMenu()
	{
		$items = [];

		$items[] = ["code" => "Games:default", "title" => "Výpis her"];
		if($this->user->isLoggedIn()){
			$items[] = ["code" => "Games:add", "title" => "+ Zadat novou hru"];
		}

		$items[] = ["code" => "Pictures:", "title" => "Seznam obrázků"];

		return $items;
	}
}
