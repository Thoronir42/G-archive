<?php

namespace App\Model\Structures;

interface IGlobalSettings
{
	public function getMaxRating();

	public function getCompletionRange();

	public function getCompletionFix();
}
