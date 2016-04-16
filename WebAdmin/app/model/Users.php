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
		$cursor = $this->collection->find();
		$users = [];

		/** @var BSONDocument $item */
		foreach($cursor as $item){
			$user = new User();
			$user->bsonUnserialize($item->getArrayCopy());
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
	 * @return User
	 */
	public function find($id)
	{
		$user = new User();
		$user->setId($id);
		$user->setEmail('email.' . $id . '@mail.cz');
		$user->setPassword('pass' . $id);
		return $user;
	}

	public function insert(User $user)
	{
		$this->collection->insertOne($user->bsonSerialize());
	}

	/**
	 * @param $BSONDocument
	 * @return null
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
}