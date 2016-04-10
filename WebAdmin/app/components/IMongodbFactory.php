<?php

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.04.2016
 * Time: 9:52
 */
interface IMongodbFactory
{
    /**
     * @return \MongoDB\Client
     */
    public function create();
}