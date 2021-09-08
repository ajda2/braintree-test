<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree;


use Braintree\Gateway;

interface GatewayFactory {

	public function create(): Gateway;
}