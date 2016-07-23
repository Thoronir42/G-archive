<?php

namespace App\Controls;

use App\Model\Picture;
use Nette\Application\UI;

class PictureView extends UI\Control
{
	/** @var string */
	private $image_directory;
	/**
	 * @var string|null
	 */
	private $default_picture;

	public function __construct($image_directory, $default_picture = null)
	{
		parent::__construct();
		$this->image_directory = $image_directory;
		$this->default_picture = $default_picture;
	}

	public function render(Picture $picture = null)
	{
		$this->template->picture = $picture;
		$this->template->directory = $this->image_directory;
		$this->template->default_picture = $this->default_picture;

		$this->template->setFile(__DIR__ . '/pictureView.latte');
		$this->template->render();
	}
}

interface IPictureViewFactory
{
	/**
	 * @param $image_directory
	 * @param null $default_picture
	 * @return PictureView
	 */
	public function create($image_directory, $default_picture = null);
}
