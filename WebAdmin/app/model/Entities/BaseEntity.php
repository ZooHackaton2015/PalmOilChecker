<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.04.2016
 * Time: 10:48
 */

namespace App\Model\Entities;


abstract class BaseEntity
{
    public function asArray()
    {
        $array = [];
        foreach($user as $field => $value){
            $array[$field] = $value;
        }
        return $array;
    }
}