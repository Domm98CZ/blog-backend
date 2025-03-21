<?php declare(strict_types=1);
namespace app;

use Nette\Bootstrap\Configurator;

final class Bootstrap
{
    /**
     * @return Configurator
     */
    public static function boot(): Configurator
    {
        $configurator = new Configurator();
        $configurator->setDebugMode(true);
        $configurator->enableTracy(__DIR__ . '/../log');
        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory(__DIR__ . '/../temp');
        $configurator->addStaticParameters(['env' => $_ENV]);

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        if (file_exists(__DIR__ . '/../config/local.neon')) {
            $configurator->addConfig(__DIR__ . '/../config/local.neon');
        }

        $configurator
            ->addConfig(__DIR__ . '/../config/main.neon')
            ->addConfig(__DIR__ . '/../config/services.neon')
        ;

        return $configurator;
    }
}
