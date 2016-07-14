<?php

namespace App\Libs\Colors;


class Colors
{
	private $hexColors;

	public function getHexColors($n){
		$colors = [];
		for ($i = 0; $i < $n; $i++) {
			$colors[] = $this->getColor($i);
		}
		return $colors;
	}

	public function getColor($i)
	{
		if(!$this->hexColors){
			$this->hexColors = $this->initColors();
		}

		return $this->hexColors[$i % count($this->hexColors)];
	}

	private function initColors()
	{
		return [
			'57FF64','5CF94D','72F343',
			'89ED3A','A1E732','BAE12A',
			'D4DC22','D6BC1A','D09513',
			'CA6E0C','C44706','BF1F00',
		];
	}
}
