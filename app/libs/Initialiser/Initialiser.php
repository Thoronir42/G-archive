<?php

namespace App\Libs;


use App\Model\Services\States;
use App\Model\State;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Kdyby\Console\StringOutput;
use Kdyby\Doctrine\Console\SchemaUpdateCommand;
use Kdyby\Doctrine\EntityManager;
use Nette\DI\Container;
use Nette\DI\Extensions\InjectExtension;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;

class Initialiser
{
	/** @var States  */
	private $states;

	/** @var SchemaUpdateCommand  */
	private $schemaUpdateCommand;

	/** @var string[]  */
	public $messages;
	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var Container
	 */
	private $context;


	public function __construct(States $states, SchemaUpdateCommand $schemaUpdateCommand, EntityManager $em, Container $context)
	{
		$this->states = $states;
		$this->messages = [];
		$this->schemaUpdateCommand = $schemaUpdateCommand;
		$this->em = $em;
		$this->context = $context;
	}

	public function initialise()
	{
		$this->initialiseSchema();
		$this->initialiseStates();
	}

	private function initialiseSchema()
	{
		$input = new ArrayInput(array('--force' => true));
		$output = new StringOutput();

		InjectExtension::callInjects($this->context, $this->schemaUpdateCommand);
		$this->schemaUpdateCommand->setHelperSet(new HelperSet(['em' => new EntityManagerHelper($this->em)]));
		$this->schemaUpdateCommand->run($input, $output);

		$this->messages[] = $output->getOutput();
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
