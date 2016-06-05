<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 05.06.2016
 * Time: 11:57
 */

namespace App\Libs;


use App\Model\Services\States;
use App\Model\State;

class Initialiser
{
	/** @var States  */
	public $states;

	/** @var string[]  */
	public $messages;
	
	public function __construct(States $states)
	{
		$this->states = $states;
		$this->messages = [];
	}

	public function initialise()
	{
		$this->initialiseStates();
	}

	private function initialiseStates(){
		$i = 0;

		$this->addState(++$i, 'Perfektni');
		$this->addState(++$i, 'Lehce opotřebený');
		$this->addState(++$i, 'Opotřebený');
		$this->addState(++$i, 'Silně Opotřebený');
		$this->addState(++$i, 'Poničený bojem');

		$this->states->flush();

		return $i;
	}
	
	private function addState($order, $label){
		$previous = $this->states->findOneBy(['label' => $label]);
		if($previous){
			$this->messages[] = "~ State '$label' already exists and was not added.";
			return;
		}

		$state = new State();

		$state->sequence = $order;
		$state->label = $label;

		$this->states->save($state, false);
		$this->messages[] = "+ Addomg state '$label'.";
	}
}