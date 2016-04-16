<?php

namespace App\Model;

use App\Model\Entities\User;
use MongoDuplicateKeyException;
use Nette;
use Nette\Security\Passwords;

use App\Model\Users;


/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{
	/** @var Users */
    private $users;


    public function __construct(Users $users)
	{
        $this->users = $users;
    }


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$user = $this->users->findByEmail($email);

		if (!$user) {
			throw new Nette\Security\AuthenticationException('Zadaný email nebyl rozpoznán.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $user->getPassword())) {
			throw new Nette\Security\AuthenticationException('Heslo nebylo zadáno správně.', self::INVALID_CREDENTIAL);
		}

		$arr = $user->asArray();
		unset($arr['password']);
		return new Nette\Security\Identity($user->getId(), 'supervisor', $arr);
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($email, $password)
	{
		try {
			$id_user = $this->users->getNextId();

			$password = Passwords::hash($password);

			$this->users->_update($id_user, $email, $password);

		} catch (MongoDuplicateKeyException $e) {
			throw new DuplicateNameException;
		}
	}

	public function update($values)
	{
		$id_user = $values->id_user * 1;
		$email = $values->email;
		$oldValues = $this->users->find($id_user)->asArray();
		if(!empty($values->password)){
			$password = Passwords::hash($values->password);
		} else {
			$password = $oldValues['password'];
		}

		$this->users->_update($id_user, $email, $password);
	}

}



class DuplicateNameException extends \Exception
{}
