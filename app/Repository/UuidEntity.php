<?php

declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


use Ramsey\Uuid\UuidInterface;

interface UuidEntity {

	public function getUuid(): UuidInterface;
}