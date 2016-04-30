<?php

namespace Libs;

use MongoClient as Client;
use Nette\DI\Container;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.04.2016
 * Time: 9:52
 */
class MongodbFactory
{
	/**
	 * @return Client
	 */
	public static function create(Container $container)
	{
		$uri = getenv('OPENSHIFT_MONGODB_DB_URL');
		if ($uri) {
			return new Client($uri);
		}
		return new Client;
	}
}
