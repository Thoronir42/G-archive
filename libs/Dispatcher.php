<?php
namespace libs;

use libs\Worker;

/**
 * Description of Dispatcher
 *
 * @author Stepan
 */
class Dispatcher {
	
    /** @var Twig_Environment */
	var $twig;
	
	/** @var PDOwrapper */
	var $pdoWrapper;
	
	/** @var libs\URLgen */
	var $urlGen;
	
	/**
	 * 
	 * @param PDOwrapper $pdoConnection
	 * @param Twig_Environment $twig
	 */
    public function __construct($pdoConnection, $twig, $urlGen) {
        $this->pdoWrapper = $pdoConnection;
		$this->twig = $twig;
		$this->urlGen = $urlGen;
    }
	
	public function dispatch($action){
		$cont = new Worker($this->pdoWrapper, $this->urlGen);
		$cont->setActiveMenuItem($action);
		
		$prepAction = $this->prepareActionName($action);
		$contResponse = $this->getControllerResponse($prepAction);
		
		$this->invokeResponse($contResponse, $cont, $action);
		
	}
	
	/**
	 * 
	 * @param array $contResponse
	 * @param controllers\Controller $cont
	 * @param string $contName
	 * @param string $action
	 * @param array $params
	 * @return type
	 */
	private function invokeResponse($contResponse, $cont, $action){
		if(empty($contResponse)){
			$this->error("$action: Nothing to do here");
			return;
		}
		if(isset($contResponse['do'])){
			$contResponse['do']->invoke($cont);
		}
		if(isset($contResponse['render'])){
			$layoutBody = $this->getLayoutPath($action);
			if(!$layoutBody){
				$this->error("No template found for $action");
				return;
			}
			$contResponse['render']->invoke($cont);
			echo $this->render($layoutBody, $cont->template);
		} else {
			$this->error("No render or redirect on $action");
		}
	}
	
	private function render($template, $vars){
		$vars['layout'] = $this->twig->loadTemplate("layout.twig");
		echo $this->twig->render($template, $vars);
	}
	
	private function error($errType){
		$vars = ["errorMessage" => $errType];
		$this->render("error.twig", $vars);
		die;
	}
	
    /**
     * 
     * @param Controler $cont
     * @param string $action
     */
    private function getControllerResponse($action){
        $contClass = new \ReflectionClass(Worker::class);
		$methodTypes = ["do", "render"];
		$return = [];
		foreach($methodTypes as $mt){
			$methodName = $mt.$action;
			if ( $contClass->hasMethod($methodName) ){
				$method = $contClass->getMethod($methodName);
				$return[$mt] = $method;
			}
		}
		
		return $return;
    }
	
	
	private function prepareActionName($action){
		if($action == null){
			return "Default";
		}
		$return = strtoupper(substr($action, 0, 1)).strtolower(substr($action, 1));
		return $return;
	}
	
	private function getLayoutPath($action){
		$dir = __DIR__."/../templates";
		//echo "$dir/$controller/$action.twig";
		if(file_exists("$dir/$action.twig")){
			$return = "$action.twig";
			return $return;
		}
		return false;
	}
}
