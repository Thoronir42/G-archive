<?php

namespace App\Presenters;

use App\Libs\GASettings;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var GASettings @inject */
	public $gameParams;

	public function startup()
	{
		parent::startup();

		$this->template->max_affection = $this->gameParams->getMaxRating();
		$this->template->menu = $this->buildMenu();
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
