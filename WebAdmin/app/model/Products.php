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
    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->collection = $this->client->hackathon->products;
    }

    public function setProductSafe($productCode, $safe)
    {

    }

    public function findLike($like, $limit)
    {
        $this->collection->find(['barcode' => 'like']);
    }
}