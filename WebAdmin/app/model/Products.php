<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:22
 */
namespace App\Model;

use App\Model\Entities\Product;
use MongoDB\BSON\UTCDatetime;
use MongoDB\Client;
use MongoDB\Driver\Cursor;
use MongoDB\Operation\FindOneAndUpdate;

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
		$date = new UTCDatetime(round(microtime(true) * 1000));
		$update = [
			'barcode' => $barcode,
			'timestamp' => $date,
			'safe' => ($safe ? 1 : 0),
			'approver_id' => $approver_id,
		];
		$options = [
			'upsert' => true,
			'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
		];
		$document = $this->collection->replaceOne($filter, $update, $options);
		return $document->isAcknowledged();
	}

	public function findMany($count, $offsetPage = 0)
	{
		$options = [
			'sort' => ['barcode' => 1],
			'limit' => $count,
			'skip' => $offsetPage * $count,
		];

		$cursor = $this->collection->find([], $options);

		return $this->cursorToProducts($cursor);
	}

	/*
	public function findLike($like, $limit = 10)
	{
		$filter = ['barcode' => $like];
		$options = ['sort' => ['barcode' => 1], 'limit' => $limit];

		$cursor = $this->collection->find($filter, $options);

		return $this->cursorToProducts($cursor);
	}
	*/

	private function cursorToProducts(Cursor $cursor)
	{
		$products = [];
		/** @var BSONDocument $item */
		foreach ($cursor as $item) {
			$user = $this->bsonToProduct($item);
			$products[] = $user;
		}

		return $products;
	}

	private function bsonToProduct($BSONDocument)
	{
		if (!$BSONDocument) {
			return null;
		}
		$rowArray = $BSONDocument->getArrayCopy();
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
		foreach ($cursor as $key =>$entry) {
			$count = $entry->count;
			$arr['total'] += $count;
			$arr[$entry->_id?'safe' : 'unsafe'] = $count;
		}
		return ($arr);
	}

	public function delete($barcode)
	{
		$this->collection->deleteOne(['barcode' => $barcode]);
	}

	public function add(Product $product)
	{
		return $this->setProductSafe($product->getBarcode(), $product->getSafe(), $product->getApproverid());
	}
}