<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree;


use Braintree\Gateway;
use Nette\SmartObject;

class GatewayManualConfigFactory implements GatewayFactory {

	use SmartObject;

	/** @var array<string, string> */
	private array $config;

	private Environment $environment;

	/**
	 * @throws \UnexpectedValueException
	 */
	public function __construct(string $env, string $merchantId, string $publicKey, string $privateKey) {
		Environment::assertValidValue($env);

		$this->config = [
			'environment' => $env,
			'merchantId'  => $merchantId,
			'publicKey'   => $publicKey,
			'privateKey'  => $privateKey,
		];
	}

	public function create(): Gateway {
		return new Gateway($this->config);
	}
}