<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest;

use Nette\Bootstrap\Configurator;


class Bootstrap {
	public static function boot(): Configurator {
		$configurator = new Configurator;
		$appDir = \dirname(__DIR__);

		//$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$configurator->setDebugMode($_SERVER['SERVER_NAME'] === 'localhost');
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator
			->addConfig($appDir . '/config/common.neon')
			->addConfig($appDir . '/config/local.neon');

		return $configurator;
	}
}