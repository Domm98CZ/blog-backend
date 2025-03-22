<?php declare(strict_types=1);

use App\Bootstrap;
use Nette\Application\Application as UIApplication;
use Contributte\Middlewares\Application\IApplication as ApiApplication;

require __DIR__ . '/../vendor/autoload.php';

$configurator = Bootstrap::boot();
$container = $configurator->createContainer();

if (substr($_SERVER['REQUEST_URI'], 0, 4) == '/api') {
    $application = $container->getByType(ApiApplication::class);
    $application->run();
} else {
    $application = $container->getByType(UIApplication::class);
    $application->run();
}
