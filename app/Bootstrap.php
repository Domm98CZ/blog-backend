<?php declare(strict_types=1);
namespace App;

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
        $configurator->addDynamicParameters([
            'env'                   => $_ENV
            , 'DATABASE_HOST'       => getenv('DATABASE_HOST')
            , 'DATABASE_USER'       => getenv('DATABASE_USER')
            , 'DATABASE_PASSWORD'   => getenv('DATABASE_PASSWORD')
            , 'DATABASE_NAME'       => getenv('DATABASE_NAME')
        ]);

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

        foreach (glob(__DIR__ . '/../config/extension/*.neon') as $file) {
            $configurator->addConfig($file);
        }

        return $configurator;
    }
}
