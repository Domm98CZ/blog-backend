<?php declare(strict_types = 1);
namespace Tests;

use Nette\DI\Container;

require __DIR__ . '/../vendor/autoload.php';

class Bootstrap
{
    public static function createContainer(): Container
    {
        $configurator = \App\Bootstrap::boot();
        $configurator->addConfig(__DIR__ . '/config.neon');
        return $configurator->createContainer();
    }

}

return Bootstrap::createContainer();
