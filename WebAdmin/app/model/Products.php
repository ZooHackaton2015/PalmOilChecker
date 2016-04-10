<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:22
 */
namespace App\Model;

use MongoDB\Client;

class Products extends BaseService
{
    const COLLECTION_NAME = 'products';

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);
    }

    public function setProductSafe($barcode, $safe)
    {
        $this->collection->updateOne(['barcode' => $barcode], ['safe' => !!$safe]);
    }

    public function findAll()
    {
        return $this->collection->find();
    }

    public function findLike($like, $limit)
    {
        return $this->collection->find(['barcode' => 'like']);
    }
}