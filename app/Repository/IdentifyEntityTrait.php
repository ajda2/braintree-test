<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


trait IdentifyEntityTrait {

	protected int $id;

	public function getId(): int {
		return $this->id;
	}
}