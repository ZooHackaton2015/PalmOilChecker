<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:31
 */

namespace App\Model;

use \MongoClient as Client;
use \MongoCollection as Collection;

abstract class BaseService
{
	const DATABASE_NAME = 'admin';

	/** @var Client */
	protected $client;

	/** @var Collection */
	protected $collection;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}


	public function count()
	{

		$cursor = $this->collection->find();
		return $cursor->count();
	}

}