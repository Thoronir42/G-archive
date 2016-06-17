<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 17.06.2016
 * Time: 14:32
 */

namespace App\Controls;

use Nette\Object;

/**
 * Class NavigationMenuItem
 * @package App\Controls
 *
 * @property $code
 * @property $caption
 *
 */
class NavigationMenuItem extends Object{

	/** @var string */
	protected $code;

	/** @var string */
	protected $caption;

	/** @var NavigationMenuItem[]  */
	private $items;

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getCaption()
	{
		return $this->caption;
	}

	/**
	 * @param string $caption
	 */
	public function setCaption($caption)
	{
		$this->caption = $caption;
	}

	/**
	 * @return bool
	 */
	public function hasItems(){
		return !empty($this->items);
	}

	/**
	 * @return NavigationMenuItem[]
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param NavigationMenuItem[] $items
	 */
	public function setItems($items)
	{
		$this->items = $items;
	}




}