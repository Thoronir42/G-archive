<?php

namespace App\Presenters;

use App\Forms\IEditGameFormFactory;
use App\Forms\IEditPlatformFormFactory;
use App\Libs\Initialiser;
use App\Model\Game;
use App\Model\Services\Games;
use App\Model\Services\Pictures;
use App\Model\Services\Platforms;
use Doctrine\ORM\Query\Expr\Base;
use Nette;
use App\Model;


class PlatformsPresenter extends BasePresenter
{
	/** @var Platforms @inject  */
	public $platforms;

	/** @var IEditPlatformFormFactory @inject  */
	public $editPlatformFormFactory;

	public function startup()
	{
		parent::startup();
		$this->steelCheck('Pro správu platforem musíš být přihlášen.');

		$this->template->title = 'Platformy';
	}

	public function actionDefault(){
		$platforms = $this->platforms->findAll();

		$this->template->platforms = $platforms;
	}

	public function actionEdit($id){
		$platform = $this->platforms->find($id);
		if(!$platform){
			$this->flashMessage('Platformu s identifikačním číslem se nepodařilo nalézt.');
			$this->redirect('default');

		}
	}

	public function actionAdd(){
		$this->setView('edit');
	}

	public function createComponentEditPlatformForm()
	{
		$form = $this->editPlatformFormFactory->create();

		return $form;
	}

}
