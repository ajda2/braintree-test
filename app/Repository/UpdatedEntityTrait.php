<?php

declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


use Nette\Utils\DateTime;

trait UpdatedEntityTrait {

	protected DateTime $updatedAt;

	public function getUpdatedAt(): DateTime {
		return $this->updatedAt;
	}
}