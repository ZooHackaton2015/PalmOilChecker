<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:31
 */

namespace App\Model;

use \MongoDB\Client;
use MongoDB\Collection;

class BaseService
{
    const DATABASE_NAME = 'admin';

    /** @var Client */
    protected $client;

    /** @var Collection  */
    protected $collection;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}