<?php

namespace App\Model\Structures;


class StaticGlobalSettings implements IGlobalSettings
{
	const MAX_RATING = 20;
	const COMPLETION_RANGE_ACCURACY = 1000;
	const COMPLETION_FIX = 100.0;


	public function getMaxRating()
	{
		return self::MAX_RATING;
	}

	public function getCompletionRange()
	{
		return self::COMPLETION_RANGE_ACCURACY;
	}

	public function getCompletionFix()
	{
		return self::COMPLETION_FIX;
	}

}
