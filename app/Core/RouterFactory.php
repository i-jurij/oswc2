<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();
        $router->addRoute('register', 'Register:');
        $router->addRoute('signin', 'Sign:in');
        $router->addRoute('politic', 'Home:politic');
        $router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

        /* for my router
        $router[] = new Route('/<param .+>', 'Homepage:default');
        */
        return $router;
    }
}
