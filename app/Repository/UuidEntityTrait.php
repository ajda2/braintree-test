<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


use Ramsey\Uuid\UuidInterface;

trait UuidEntityTrait {

	protected UuidInterface $uuid;

	public function getUuid(): UuidInterface {
		return $this->uuid;
	}
}