<?php

namespace Libs;

use Nette\Security\User;
use App\Model\Entities\User as EntUser;
use Nette\Utils\ArrayHash;

/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 09.04.2016
 * Time: 17:58
 */
class NavbarBuilder
{
    public static function createNavbar(User $user)
    {
        $menu = [];
        $menu['appName'] = 'Palm oil checker';
        $menu['links'] = [
            ['label' => 'Produktové kódy', 'code' => 'Products:'],
        ];
        if ($user->isInRole(EntUser::ROLE_ADMIN)) {
            $menu['links'][] = ['label' => 'Správa uživatelů', 'code' => 'Users:'];
        }

        return ArrayHash::from($menu);

    }
}