<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 25.06.2016
 * Time: 12:43
 */

namespace App\Controls;

use App\Model\Platform;
use Nette\Application\UI as UI;

class PlatformView extends UI\Control
{
	public function renderSingle(Platform $platform){
		$this->template->setFile(__DIR__ . '/platformSingle.latte');

		$this->template->platform = $platform;

		$this->template->render();
	}

	public function renderNavigation($platforms){
		$this->template->setFile(__DIR__ . '/platformNavigation.latte');

		$this->template->platforms = $platforms;

		$this->template->render();
	}

	public function createComponentGame()
	{
		return new GameView();
	}
}