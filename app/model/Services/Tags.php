<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 22.06.2016
 * Time: 20:00
 */

namespace App\Model\Services;


use App\Model\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\EntityManager;

class Tags extends BaseService
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(Tag::class));
	}

	/**
	 * @param string[] $tag_titles
	 * @return Tag[]
	 */
	public function getTags($tag_titles)
	{
		$return_tags = $this->findBy(['title' => $tag_titles]);
		if(sizeof($return_tags) == sizeof($tag_titles)){
			return $return_tags;
		}
		foreach ($tag_titles as $title){
			$tag = $this->findOneBy(['title' => $title]);
			if(!$tag){
				$tag = new Tag();
				$tag->title = $title;
				$this->save($tag, false);
				$return_tags[] = $tag;
			}
		}
		$this->flush();
		return $return_tags;
	}

	public function getAllTitles()
	{
		$tags = $this->findAll();
		$titles = [];
		/** @var Tag $tag */
		foreach ($tags as $tag){
			$titles[$tag->title] = $tag->title;
		}

		return $titles;
	}
}