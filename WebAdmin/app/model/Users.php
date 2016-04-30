<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 18:06
 */

namespace App\Model;

use App\Model\Entities\User;
use MongoClient;


class Users extends BaseService
{
	const COLLECTION_NAME = 'users';

	public function __construct(MongoClient $client)
	{
		parent::__construct($client);
		$this->collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);
	}

	public function getNextId(){
		$options = [
			'sort' => ['id_user' => -1],
		];
		$cursor = $this->collection->find();
		$cursor->sort($options['sort']);
		$user = $cursor->getNext();
		$next_id = $user['id_user'] + 1;
		return $next_id;
	}

	public function findMany($count = 10, $offsetPage = 0)
	{
		$options = [
			'sort' => ['id_user' => 1],
			'limit' => $count,
			'skip' => $offsetPage * $count
		];


		$cursor = $this->collection->find();
		$cursor->skip($options['skip']);
		$cursor->limit($options['limit']);

		$users = [];
		while($item = $cursor->getNext()){
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

		$rowArray = (array)$BSONDocument;

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

	/**
	 * @param $id
	 * @return User|null
	 */
	public function delete($id)
	{
		$user = $this->collection->findOne(['id_user' => $id * 1]);
		$result = $this->collection->remove(['id_user' => $id * 1]);
		return $this->bsonToUser($user);
	}

	/** to be only used from UserManager to keep password hashing at one place */
	public function _update($id_user, $email, $password, $role = User::ROLE_CONTRIBUTOR)
	{
		$filter = ['id_user' => $id_user];
		$update = [
			'id_user' => $id_user,
			'email' => $email,
			'password' => $password,
			'role' => $role,
		];
		$options = [
			'upsert' => true,
			'new' => true,
		];
		$document = $this->collection->findAndModify($filter, $update, null, $options);

		return $document;
	}
}