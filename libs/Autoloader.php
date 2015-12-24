<?php
include __DIR__.'/Twig/Autoloader.php';
Twig_Autoloader::register(true);

spl_autoload_register('Autoloader::NamespaceLoader');



class Autoloader{
	public static function NamespaceLoader($class){
		if (self::tryInclude(__DIR__."/../".$class)){ return true; }
		return false;
	}
    
    private function tryInclude($path){
        if(file_exists($path.".php")){            
            include $path.".php";
            return true;
        }
        return false;
    }
}

