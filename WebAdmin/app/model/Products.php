<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:22
 */
namespace App\Model;

use App\Model\Entities\Product;
use MongoDate;
use MongoClient as Client;
use MongoCursor as Cursor;

class Products extends BaseService
{
	const COLLECTION_NAME = 'products';

	public function __construct(Client $client)
	{
		parent::__construct($client);
		$this->collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);
	}

	public function setProductSafe($barcode, $safe, $approver_id = 0)
	{
		$filter = ['barcode' => $barcode];
		$date = new MongoDate(strtotime('now'));

		$update = [
			'barcode' => $barcode,
			'timestamp' => $date,
			'safe' => ($safe ? 1 : 0),
			'approver_id' => $approver_id,
		];
		$options = [
			'upsert' => true,
		];
		$document = $this->collection->findAndModify($filter, $update, null, $options);
		return $document;
	}

	public function findMany($count, $offsetPage = 0)
	{
		$options = [
			'sort' => ['barcode' => 1],
			'limit' => $count,
			'skip' => $offsetPage * $count,
		];

		$cursor = $this->collection->find();
		$cursor->sort($options['sort']);
		$cursor->skip($options['skip']);
		$cursor->limit($options['limit']);

		return $this->cursorToProducts($cursor);
	}

	private function cursorToProducts(Cursor $cursor)
	{
		$products = [];

		while ($product = $cursor->getNext()) {
			$products[] = $this->bsonToProduct($product);
		}

		return $products;
	}

	private function bsonToProduct($BSONDocument)
	{
		if (!$BSONDocument) {
			return null;
		}
		$rowArray = $BSONDocument;
		unset($rowArray['_id']);
		$user = new Product();
		$user->bsonUnserialize($rowArray);
		return $user;
	}

	public function count()
	{
		$cursor = $this->collection->aggregate([
			['$group' => ['_id' => '$safe', 'count' => ['$sum' => 1]]]
		]);
		$arr = ['total' => 0];
		$arr['safe'] = $arr['unsafe'] = 0;

		foreach ($cursor['result'] as $entry) {
			$count = $entry['count'];
			$arr['total'] += $count;
			$arr[$entry['_id'] ? 'safe' : 'unsafe'] = $count;
		}
		return ($arr);
	}

	public function delete($barcode)
	{
		$this->collection->remove(['barcode' => $barcode]);
	}

	public function add(Product $product)
	{
		return $this->setProductSafe($product->getBarcode(), $product->getSafe(), $product->getApproverid());
	}
}