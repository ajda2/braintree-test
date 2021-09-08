<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


use Nette\Utils\DateTime;

interface UpdatedEntity {

	public function getUpdatedAt(): DateTime;
}