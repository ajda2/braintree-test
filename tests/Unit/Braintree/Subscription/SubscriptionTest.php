<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Tests\Unit\Braintree\Subscription;


use Codeception\Test\Unit;
use Mrcek\BraintreeTest\Braintree\Subscription\Subscription;
use Nette\Utils\DateTime;
use Ramsey\Uuid\Uuid;

class SubscriptionTest extends Unit {

	public function testConstruct(): void {
		$id = 0;
		$uuid = Uuid::uuid4();
		$braintreeId = 'brain-id';
		$braintreePlanId = 'braintree plan id';
		$braintreeCustomerId = 'braintree customer id';
		$createdAt = new DateTime();
		$updatedAt = new DateTime('-2 days');
		$price = '50';
		$currencyIso = 'CZK';
		$status = 'Active';
		$billingPeriodStartAt = new DateTime('+1 days');
		$billingPeriodEndAt = new DateTime('+10 days');
		$firstBillingAt = new DateTime('+11 days');
		$nextBillingAt = new DateTime('+31 days');
		$paidThroughDate = new DateTime('+50 days');
		$braintreeMerchantAccountId = 'merchant ID';
		$neverExpires = true;
		$nextBillingAmount = '666';
		$paymentMethodToken = 'payment TOKEN';

		$item = new Subscription(
			$id,
			$uuid,
			$braintreeId,
			$braintreePlanId,
			$braintreeCustomerId,
			$createdAt,
			$updatedAt,
			$price,
			$currencyIso,
			$status,
			$billingPeriodStartAt,
			$billingPeriodEndAt,
			$firstBillingAt,
			$nextBillingAt,
			$paidThroughDate,
			$braintreeMerchantAccountId,
			$neverExpires,
			$nextBillingAmount,
			$paymentMethodToken
		);


		$this::assertSame($id, $item->getId());
		$this::assertSame($uuid->toString(), $item->getUuid()->toString());
		$this::assertSame($braintreeId, $item->getBraintreeId());
		$this::assertSame($braintreePlanId, $item->getBraintreePlanId());
		$this::assertSame($braintreeCustomerId, $item->getBraintreeCustomerId());
		$this::assertSame($createdAt->getTimestamp(), $item->getCreatedAt()->getTimestamp());
		$this::assertSame($updatedAt->getTimestamp(), $item->getUpdatedAt()->getTimestamp());
		$this::assertSame($price, $item->getPrice());
		$this::assertSame($currencyIso, $item->getCurrencyIso());
		$this::assertSame($status, $item->getStatus());
		$this::assertSame($billingPeriodStartAt->getTimestamp(), $item->getBillingPeriodStartAt()->getTimestamp());
		$this::assertSame($billingPeriodEndAt->getTimestamp(), $item->getBillingPeriodEndAt()->getTimestamp());
		$this::assertSame($firstBillingAt->getTimestamp(), $item->getFirstBillingAt()->getTimestamp());
		$this::assertNotNull($item->getNextBillingAt());
		$this::assertNotNull($item->getPaidThroughDate());
		$this::assertSame($nextBillingAt->getTimestamp(), $item->getNextBillingAt()->getTimestamp());
		$this::assertSame($paidThroughDate->getTimestamp(), $item->getPaidThroughDate()->getTimestamp());
		$this::assertSame($braintreeMerchantAccountId, $item->getBraintreeMerchantAccountId());
		$this::assertTrue($item->isNeverExpires());
		$this::assertSame($nextBillingAmount, $item->getNextBillingPeriodAmount());
		$this::assertSame($paymentMethodToken, $item->getPaymentMethodToken());
	}

	// TODO: create test for Subscription::fromBraintree()
}