<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.04.2016
 * Time: 9:35
 */

namespace Libs;


use MongoDB\Client;
use Nette\DI\Container;

class MongoFactory
{

    /**
     * @return Client
     */
    public static function createMongoClient($arguments)
    {
        dump($arguments);exit;
        return new Client();
    }
}