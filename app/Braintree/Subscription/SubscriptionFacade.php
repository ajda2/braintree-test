<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Subscription;


use Mrcek\BraintreeTest\Repository\Exceptions\InsertException;
use Mrcek\BraintreeTest\Repository\Exceptions\UpdateException;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tracy\ILogger;

class SubscriptionFacade {

	use SmartObject;

	private SubscriptionRepository $repository;

	private ILogger $logger;

	public function __construct(SubscriptionRepository $repository, ILogger $logger) {
		$this->repository = $repository;
		$this->logger = $logger;
	}

	/**
	 * @throws InsertException
	 * @throws UpdateException
	 */
	public function saveBraintreeSubscription(
		\Braintree\Subscription $subscription,
		string $braintreeCustomerId,
		string $currencyIso
	): Subscription {
		try {
			$_subscription = Subscription::fromBraintree(
				$subscription,
				$braintreeCustomerId,
				$this->createUuid(),
				$currencyIso
			);
		} catch (\Throwable $e) {
			$this->logger->log($e, $this->logger::ERROR);
			throw new InsertException($e->getMessage(), $e->getCode(), $e);
		}

		return $this->repository->save($_subscription);
	}

	public function createUuid(): UuidInterface {
		return Uuid::uuid4();
	}

	public function findByUuid(UuidInterface $uuid): ?Subscription {
		$by = [
			$this->repository::COLUMN_UUID => $uuid,
		];

		$result = $this->repository->findBy($by, null, 1);

		if ($result->offsetExists(0)) {
			return $result->offsetGet(0);
		}

		return null;
	}
}