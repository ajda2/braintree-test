<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Tests\Integration;

use Codeception\Actor;
use Codeception\Module\Db;
use Codeception\Test\Unit;
use Contributte\Codeception\Module\NetteDIModule;

class IntegrationTestCase extends Unit {

	/** @var Actor|NetteDIModule|Db */
	protected $tester;

}