<?php

namespace App\Model\Entities;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 17:01
 */
class User extends BaseEntity
{
	public $id_user;

	public $email;

	public $password;

	public function __construct($id = 0)
	{
		parent::__construct();
		$this->id_user = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id_user;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id_user = $id;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}


}