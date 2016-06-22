<?php

namespace App\Presenters;

use App\Forms\EditPlatformForm;
use App\Forms\IEditPlatformFormFactory;
use App\Libs\ImageManager;
use App\Model\Platform;
use App\Model\PlatformPicture;
use App\Model\Services\Pictures;
use App\Model\Services\Platforms;

use App\Model\Services\States;
use App\Model\State;
use Nette;
use App\Model;
use Nette\Application\UI\Form;


class StatesPresenter extends BasePresenter
{
	/** @var States @inject */
	public $states;

	/** @var IEditPlatformFormFactory @inject */
	public $editPlatformFormFactory;

	public function startup()
	{
		parent::startup();
		$this->steelCheck('Pro správu stavů musíš být přihlášen.', []);

		$this->template->title = 'Stavy';
	}

	public function renderDefault()
	{
		$states = $this->states->findBy([], ['sequence' => 'ASC']);

		$this->template->states = $states;
	}

	public function handleAdd($label = '')
	{
		$state = $this->states->findOneBy(['label' => $label]);
		if($state || !$label){
			return;
		}
		$state = new State();
		$state->label = $label;
		$this->states->save($state);

		$this->renderDefault();
		$this->redrawControl('states');
	}

	public function handleDelete($id){
		$state = $this->states->find($id);
		if(!$state){
			return;
		}

		$this->states->delete($state);

		$this->renderDefault();
		$this->redrawControl('states');
	}

	public function handleUndelete($id)
	{
		$state = $this->states->find($id);
		if(!$state){
			return;
		}

		$this->states->undelete($state);

		$this->renderDefault();
		$this->redrawControl('states');
	}

	public function handleSort()
	{
		$order = $this->getParameter('sort');
		foreach ($order as $sequence => $id){
			/** @var State $state */
			$state = $this->states->find($id);
			$state->sequence = $sequence + 1;
			$this->states->save($state, false);
		}

		$this->states->flush();
	}

}