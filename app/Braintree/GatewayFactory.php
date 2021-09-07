<?php

declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree;


use Braintree\Gateway;
use Mrcek\BraintreeTest\Config\Code;
use Mrcek\BraintreeTest\Config\ConfigFacade;
use Mrcek\BraintreeTest\Repository\Exceptions\NotFoundException;
use Nette\SmartObject;

class GatewayFactory {

	use SmartObject;

	private ConfigFacade $configFacade;

	public function __construct(ConfigFacade $configFacade) {
		$this->configFacade = $configFacade;
	}

	/**
	 * @throws NotFoundException
	 */
	public function create(Environment $env): Gateway {
		return new Gateway(
			[
				'environment' => $env->getValue(),
				'merchantId'  => (string)$this->configFacade->getValue(new Code(Code::BRAINTREE_MERCHANT_ID)),
				'publicKey'   => (string)$this->configFacade->getValue(new Code(Code::BRAINTREE_PUBLIC_KEY)),
				'privateKey'  => (string)$this->configFacade->getValue(new Code(Code::BRAINTREE_PRIVATE_KEY))
			]
		);
	}
}