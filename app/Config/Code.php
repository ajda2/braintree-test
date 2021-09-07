<?php

declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Config;


use MyCLabs\Enum\Enum;

/**
 * @extends  Enum<string>
 */
class Code extends Enum {

	public const
		BRAINTREE_MERCHANT_ID = "braintree_merchant_id",
		BRAINTREE_PUBLIC_KEY = "braintree_public_key",
		BRAINTREE_PRIVATE_KEY = "braintree_private_key",
		BRAINTREE_ENVIRONMENT = "braintree_environment";
}