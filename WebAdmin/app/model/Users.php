<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:06
 */

namespace App\Model;

use App\Model\Entities\User;
use MongoDB\Client;
use MongoDB\Model\BSONDocument;


class Users extends BaseService
{
	const COLLECTION_NAME = 'users';

	public function __construct(Client $client)
	{
		parent::__construct($client);
		$this->collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);
	}

	public function getNextId(){
		$options = [
			'sort' => ['id_user' => -1],
		];
		$last = $this->collection->findOne([], $options);
		if(!$last){
			$next_id = 1;
		} else {
			$next_id = $last->id_user + 1;
		}
		return $next_id;
	}

	public function findMany($count = 10, $offsetPage = 0)
	{
		$options = [
			'sort' => ['id_user' => 1],
			'limit' => $count,
			'skip'

		];

		$cursor = $this->collection->find();

		$users = [];
		/** @var BSONDocument $item */
		foreach($cursor as $item){
			$user = $this->bsonToUser($item);
			$users[] = $user;
		}

		return $users;
	}

	/**
	 * @param $email
	 * @return User|null
	 */
	public function findByEmail($email)
	{
		/** @var BSONDocument $row */
		$row = $this->collection->findOne(['email' => $email]);
		return $this->bsonToUser($row);
	}

	/**
	 * @param $id
	 * @return User|null
	 */
	public function find($id)
	{
		$row = $this->collection->findOne(['id_user' => $id * 1]);
		return $this->bsonToUser($row);
	}

	/**
	 * @param $BSONDocument
	 * @return User|null
	 */
	private function bsonToUser($BSONDocument){
		if(!$BSONDocument){
			return null;
		}
		$rowArray = $BSONDocument->getArrayCopy();
		unset($rowArray['_id']);
		$user = new User();
		$user->bsonUnserialize($rowArray);
		return $user;
	}

	public function hasEntries()
	{
		$entry = $this->collection->findOne();
		return !!$entry;
	}

	public function count()
	{
		$cursor = $this->collection->aggregate([
			['$group' => ['_id' => 'foo', 'count' => ['$sum' => 1]]]
		]);
		foreach($cursor as $entry){
			return ($entry->count);
		}
		return 0;
	}

	/**
	 * @param $id
	 * @return User|null
	 */
	public function delete($id)
	{
		$user = $this->collection->findOne(['id_user' => $id * 1]);
		$this->collection->deleteOne(['id_user' => $id * 1]);
		return $this->bsonToUser($user);
	}

	/** to be only used from UserManager to keep password hashing at one place */
	public function _update($id_user, $email, $password)
	{
		$filter = ['id_user' => $id_user];
		$update = [
			'id_user' => $id_user,
			'email' => $email,
			'password' => $password,
		];
		$options = [
			'upsert' => true,
		];
		$document = $this->collection->replaceOne($filter, $update, $options);
		return $document->isAcknowledged();
	}
}