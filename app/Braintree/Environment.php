<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree;


use MyCLabs\Enum\Enum;

/**
 * @extends  Enum<string>
 */
class Environment extends Enum {

	public const
		SANDBOX = "sandbox",
		PRODUCTION = "production";
}