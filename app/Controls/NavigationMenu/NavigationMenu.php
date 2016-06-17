<?php

namespace App\Controls;

use Nette\Application\UI;
use Nette\Object;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 17.06.2016
 * Time: 14:13
 */
class NavigationMenu extends UI\Control
{

	protected $title;

	/** @var NavigationMenuItem[]  */
	private $items;

	public function __construct()
	{
		parent::__construct();

		$this->title = "";
		$this->items = [];
	}

	public function addItem($code, $caption)
	{
		$item = new NavigationMenuItem();
		$item->code = $code;
		$item->caption = $caption;

		return $this->items[] = $item;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}



	public function renderTop(){
		$this->template->setFile(__DIR__ . "/topMenu.latte");

		$this->template->title = $this->title;
		$this->template->items = $this->items;

		$this->template->render();
	}
}

interface INavigationMenuFactory{

	/** @return NavigationMenu */
	public function create();
}