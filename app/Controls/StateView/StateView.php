<?php

namespace App\Controls;

use App\Libs\Colors\Colors;
use App\Model\Services\States;
use App\Model\State;
use Nette\Application\UI;
use Tracy\Debugger;

class StateView extends UI\Control
{
	/** @var States */
	private $states;

	/** @var State[] */
	private $ordered;
	/** @var Colors */
	private $colors;

	public function __construct(States $states, Colors $colors)
	{
		parent::__construct();
		$this->states = $states;

		$this->ordered = $this->states->findBy([], ['sequence' => 'ASC']);
		$this->colors = $colors;
	}

	public function renderStyles()
	{
		$colors = [];
		/** @var State $state */
		foreach ($this->ordered as $state){
			$colors[$state->class] = $this->colors->getColor($state->sequence);
		}

		$this->template->setFile(__DIR__ . '/stateStyles.latte');

		$this->template->colors = $colors;

		$this->template->render();

	}
}

interface IStateViewFactory{

	/**
	 * @return StateView
	 */
	public function create();
}
