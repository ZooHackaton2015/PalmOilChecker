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


class Users extends BaseService
{
    const COLLECTION_NAME = 'users';


    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);
    }
    
    public function findAll()
    {
        $users = [];

        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setId($i);
            $user->setEmail('email.'.$i.'@mail.cz');
            $user->setPassword('pass'.$i);
            $users[] = $user;
        }

        return $users;
    }

    public function findByEmail($email)
    {
    }

    /**
     * @param $id
     * @return User
     */
    public function find($id)
    {
        $user = new User();
        $user->setId($id);
        $user->setEmail('email.'.$id.'@mail.cz');
        $user->setPassword('pass'.$id);
        return $user;
    }
}