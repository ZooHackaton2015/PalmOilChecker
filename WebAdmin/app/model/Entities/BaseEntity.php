<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.04.2016
 * Time: 10:48
 */

namespace App\Model\Entities;

use MongoDB\BSON\Persistable;


abstract class BaseEntity implements Persistable
{
	protected $unserialized;

	public function __construct()
	{
		$this->unserialized = false;
	}

	public function asArray()
	{
		$array = [];
		foreach ($this as $field => $value) {
			$array[$field] = $value;
		}
		unset($array['unserialized']);
		return $array;
	}

	public function bsonSerialize()
	{
		return $this->asArray();
	}

	public function bsonUnserialize(array $data)
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
		$this->unserialized = true;
	}


}