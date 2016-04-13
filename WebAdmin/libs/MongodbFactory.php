<?php

namespace Libs;

use MongoDB\Client;
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
	 * @return \MongoDB\Client
	 */
	public static function create(Container $container)
	{
		$uri = filter_input(INPUT_ENV, 'OPENSHIFT_MONGODB_DB_URL');
		if ($uri) {
			return new Client($uri);
		}
		return new Client;
	}
}
