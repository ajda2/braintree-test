<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Tests\Integration;


use Nette\Configurator;
use Nette\DI\Container;

class Bootstrap {

	public static ?Container $container = null;

	public static function getContainer(): Container {
		if (self::$container !== null) {
			return self::$container;
		}

		$configurator = new Configurator();
		$configurator->setDebugMode(true);
		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/temp');
		$configurator->enableTracy(__DIR__ . '/log');

		$configurator->addConfig(__DIR__ . '/../../config/common.neon');
		$configurator->addConfig(__DIR__ . '/config.integration.neon');

		$configurator->addParameters(
			[
				'appDir' => __DIR__ . '/../../app',
			]
		);

		self::$container = $configurator->createContainer();

		return self::$container;
	}
}
