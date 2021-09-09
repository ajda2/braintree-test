<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Tests\Integration;

use Codeception\Exception\ModuleException;
use Codeception\Module\Db;
use Codeception\Test\Unit;
use Nette\DI\Container;

class IntegrationTestCase extends Unit {

	protected Container $container;

	/**
	 * @throws ModuleException
	 */
	protected function getDb(): Db {
		$module = $this->getModule('Db');

		if (!$module instanceof Db) {
			throw new \Exception('Db module not found');
		}

		return $module;
	}

}