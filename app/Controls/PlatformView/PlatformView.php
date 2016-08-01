<?php

namespace App\Controls;

use App\Model\Platform;
use Nette\Application\UI as UI;

class PlatformView extends UI\Control
{
	/** @var IGameViewFactory */
	private $game_view_factory;

	private $list_template;

	public function __construct(IGameViewFactory $game_view_factory)
	{
		parent::__construct();
		$this->game_view_factory = $game_view_factory;
		$this->setListBootstrapTemplate(false);
	}

	public function setListBootstrapTemplate($bool){
		$file = $bool ?
			__DIR__ . '/platformNavigationBs.latte' :
			__DIR__ . '/platformNavigation.latte';

		$this->list_template = $file;
	}

	public function renderSingle(Platform $platform)
	{
		$this->template->setFile(__DIR__ . '/platformSingle.latte');

		$this->template->platform = $platform;

		$this->template->render();
	}

	public function renderNavigation($platforms, $additional_links = [])
	{
		$this->template->setFile($this->list_template);

		$this->template->platforms = $platforms;
		$this->template->additional_links = $additional_links;

		$this->template->render();
	}

	public function createComponentGame()
	{
		return $this->game_view_factory->create();
	}
}

interface IPlatformViewFactory
{

	/** @return PlatformView */
	public function create();
}
