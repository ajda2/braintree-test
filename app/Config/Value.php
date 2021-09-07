<?php

declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Config;


use Mrcek\BraintreeTest\Repository\IdentifyEntity;
use Mrcek\BraintreeTest\Repository\IdentifyEntityTrait;
use Nette\SmartObject;

class Value implements IdentifyEntity, \Stringable {

	use SmartObject;
	use IdentifyEntityTrait;

	private string $code;

	private string $value;

	public function __construct(int $id, string $code, string $value) {
		$this->id = $id;
		$this->code = $code;
		$this->value = $value;
	}

	public function getCode(): string {
		return $this->code;
	}

	public function getValue(): string {
		return $this->value;
	}

	public function __toString(): string {
		return $this->getValue();
	}
}