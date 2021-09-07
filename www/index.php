<?php declare(strict_types = 1);

use Mrcek\BraintreeTest\Bootstrap;
use Nette\Application\Application;

require __DIR__ . '/../vendor/autoload.php';

$configurator = Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Application::class);
$application->run();