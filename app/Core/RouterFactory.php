<?php declare(strict_types=1);
namespace app\Core;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    public static function createRouter(): RouteList
    {
        $router = new RouteList;

        // default routa
        $router->addRoute('<presenter>/<action>[/<id>]', [
            'module' => 'Front',
            'presenter' => 'Home',
            'action' => 'default',
            'id' => null,
        ]);

        return $router;
    }
}
