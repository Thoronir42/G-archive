<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 18.06.2016
 * Time: 17:41
 */

namespace App\Model\Services;


use App\Model\Platform;
use Kdyby\Doctrine\EntityManager;

class Platforms extends BaseService
{

	/**
	 * Platforms constructor.
	 */
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getRepository(Platform::class));
	}
}