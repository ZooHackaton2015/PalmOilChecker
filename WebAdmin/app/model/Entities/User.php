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
	const ROLE_ADMIN = 'admin';
	const ROLE_CONTRIBUTOR = 'contributor';
	const ROLE_USER = 'user';

	private static $ROLES = [
		self::ROLE_ADMIN => 'Administrátor',
		self::ROLE_CONTRIBUTOR => 'Přispěvatel',
		self::ROLE_USER => 'Uživatel',
	];

	public static function getRoles()
	{
		$roles = self::$ROLES;
		return $roles;
	}

	public $id_user;

	public $email;

	public $password;

	public $role;

	public function __construct($id = 0)
	{
		parent::__construct();
		$this->id_user = $id;
	}

	public static function verifyRole($getRole)
	{
		if(array_key_exists($getRole, self::$ROLES)){
			return $getRole;
		}
		return false;
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

	/**
	 * @return mixed
	 */
	public function getRole()
	{
		return $this->role;
	}

	public function getRoleLabel(){
		switch($this->role){
			default:
				return 'Neznámá role';
			case self::ROLE_ADMIN:
				return 'Administrátor';
			case self::ROLE_CONTRIBUTOR:
				return 'Přispěvatel';
			case self::ROLE_USER:
				return 'Přihlížející';
		}
	}

	/**
	 * @param mixed $role
	 */
	public function setRole($role)
	{
		if(!array_key_exists($role, self::$ROLES)){
			throw new \InvalidArgumentException($role . ' is not valid user role');
		}
		$this->role = $role;
	}




}