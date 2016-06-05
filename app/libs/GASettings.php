<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 02.06.2016
 * Time: 9:24
 */

namespace App\Libs;


class GASettings
{
	const MAX_RATING = 20;

	const COMPLETION_RANGE_ACCURACY = 1000;
	const COMPLETION_FIX = 100.0;


	/** @var GASettings  */
	private static $instance;

	/** @var GASettings */
	public static function instance(){
		if(!self::$instance){
			self::$instance = new GASettings();
		}
		return self::$instance;
	}


	public function getMaxRating(){
		return self::MAX_RATING;
	}

	public function getCompletionRange(){
		return self::COMPLETION_RANGE_ACCURACY;
	}

	public function getCompletionFix()
	{
		return self::COMPLETION_FIX;
	}

}