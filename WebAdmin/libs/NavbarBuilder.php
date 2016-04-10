<?php

namespace Libs;

use Nette\Utils\ArrayHash;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 17:58
 */
class NavbarBuilder
{
    public static function createNavbar(){
    $menu = [];
    $menu['appName'] = 'Palm oil checker';
    $menu['links'] = [
        ['label' => 'Produktové kódy',  'code' => 'Products:'],
        ['label' => 'Správa uživatelů',  'code' => 'Users:'],
    ];

        return ArrayHash::from($menu);

    }
}