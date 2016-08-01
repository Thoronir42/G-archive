<?php

namespace App\Presenters;

use App\Forms\EditPlatformForm;
use App\Forms\IEditPlatformFormFactory;
use App\Libs\ImageManager;
use App\Model\Platform;
use App\Model\PlatformPicture;
use App\Model\Services\Pictures;
use App\Model\Services\Platforms;

use Nette;
use App\Model;
use Nette\Application\UI\Form;


class PlatformsPresenter extends BasePresenter
{
	/** @var Platforms @inject */
	public $platforms;

	/** @var Pictures @inject */
	public $pictures;

	/** @var ImageManager @inject */
	public $imageManager;

	/** @var IEditPlatformFormFactory @inject */
	public $editPlatformFormFactory;

	public function startup()
	{
		parent::startup();
		$this->steelCheck('Pro správu platforem musíš být přihlášen.');

		$this->template->title = 'Platformy';
	}

	public function actionDefault()
	{
		$platforms = $this->platforms->findAll();

		$this->template->platforms = $platforms;

	}

	public function actionEdit($id)
	{
		$platform = $this->platforms->find($id);
		if (!$platform) {
			$this->flashMessage('Platformu s identifikačním číslem se nepodařilo nalézt.');
			$this->redirect('default');
		}

		/** @var EditPlatformForm $form */
		$form = $this['editPlatformForm'];

		$form->setEntity($platform);

	}

	public function actionAdd()
	{
		$this->setView('edit');
	}

	// handles

	public function handleSort()
	{
		dump($_POST);
		exit;
	}

	// components

	public function createComponentEditPlatformForm()
	{
		$form = $this->editPlatformFormFactory->create();

		$form->onSave[] = function (Form $form, Platform $platform, PlatformPicture $picture = null) {
			if ($picture) {
				$this->pictures->save($picture);
				if($platform->picture){
					$this->pictures->delete($platform->picture);
					$this->imageManager->delete($platform->picture);
				}

				$platform->picture = $picture;
			}
			$this->platforms->save($platform);
			
			$this->flashMessage('Platforma ' . $platform->title . ' byla uložena.');
			$this->redirect('default');
		};

		return $form;
	}

}
