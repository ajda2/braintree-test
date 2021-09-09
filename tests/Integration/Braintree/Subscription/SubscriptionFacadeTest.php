<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Tests\Integration\Braintree\Subscription;


use Codeception\Module\Db;
use Mrcek\BraintreeTest\Braintree\Subscription\Subscription;
use Mrcek\BraintreeTest\Braintree\Subscription\SubscriptionFacade;
use Mrcek\BraintreeTest\Braintree\Subscription\SubscriptionRepository;
use Mrcek\BraintreeTest\Tests\Integration\Bootstrap;
use Mrcek\BraintreeTest\Tests\Integration\IntegrationTestCase;
use Ramsey\Uuid\Uuid;

class SubscriptionFacadeTest extends IntegrationTestCase {

	private SubscriptionFacade $subscriptionFacade;

	private Db $db;

	public function _before(): void {
		parent::_before();

		$container = Bootstrap::getContainer();
		$this->subscriptionFacade = $container->getByType(SubscriptionFacade::class);
		$this->db = $this->getDb();
	}

	public function testFindByUuid(): void {
		$uuid = Uuid::fromString('98da39a4-d2ad-49f9-b988-e8f8c0c9f912');
		$result = $this->subscriptionFacade->findByUuid($uuid);

		$this::assertInstanceOf(Subscription::class, $result);
		$this::assertSame(1, $result->getId());
		$this::assertSame($uuid->toString(), $result->getUuid()->toString());
	}

	public function testFindByUuidNotFound(): void {
		$uuid = Uuid::fromString('98da39a4-d2ad-49f9-b988-e8f8c0c9f965');
		$result = $this->subscriptionFacade->findByUuid($uuid);

		$this::assertNull($result);
	}

	public function testSaveBraintreeSubscription(): void {
		$braintreeId = 'brain-id';
		$braintreePlanId = 'braintree plan id';
		$braintreeCustomerId = 'braintree customer id';
		$createdAt = new \DateTime();
		$updatedAt = new \DateTime('-2 days');
		$price = '50';
		$currencyIso = 'CZK';
		$status = 'Active';
		$billingPeriodStartAt = new \DateTime('+1 days');
		$billingPeriodEndAt = new \DateTime('+10 days');
		$firstBillingAt = new \DateTime('+11 days');
		$nextBillingAt = new \DateTime('+31 days');
		$paidThroughDate = new \DateTime('+50 days');
		$braintreeMerchantAccountId = 'merchant ID';
		$neverExpires = true;
		$nextBillingAmount = '666';
		$paymentMethodToken = 'payment TOKEN';

		$braintreeSubs = $this->createBraintreeSubs(
			$braintreeId,
			$createdAt,
			$updatedAt,
			$billingPeriodStartAt,
			$billingPeriodEndAt,
			$firstBillingAt,
			$nextBillingAt,
			$paidThroughDate,
			$braintreePlanId,
			$price,
			$status,
			$braintreeMerchantAccountId,
			$neverExpires,
			$nextBillingAmount,
			$paymentMethodToken
		);

		$this->subscriptionFacade->saveBraintreeSubscription($braintreeSubs, $braintreeCustomerId, $currencyIso);

		$this->db->seeInDatabase(
			SubscriptionRepository::TABLE_NAME,
			[
				SubscriptionRepository::COLUMN_BRAINTREE_ID              => $braintreeId,
				SubscriptionRepository::COLUMN_BRAINTREE_PLAN_ID         => $braintreePlanId,
				SubscriptionRepository::COLUMN_BRAINTREE_CUSTOMER_ID     => $braintreeCustomerId,
				SubscriptionRepository::COLUMN_CREATED_AT                => $createdAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_UPDATED_AT                => $updatedAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_PRICE                     => $price,
				SubscriptionRepository::COLUMN_CURRENCY_ISO              => $currencyIso,
				SubscriptionRepository::COLUMN_STATUS                    => $status,
				SubscriptionRepository::COLUMN_BILLING_PERIOD_START_DATE => $billingPeriodStartAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_BILLING_PERIOD_END_DATE   => $billingPeriodEndAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_FIRST_BILLING_DATE        => $firstBillingAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_NEXT_BILLING_DATE         => $nextBillingAt->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_PAID_THROUGH_DATE         => $paidThroughDate->format('Y-m-d H:i:s'),
				SubscriptionRepository::COLUMN_MERCHANT_ACCOUNT_ID       => $braintreeMerchantAccountId,
				SubscriptionRepository::COLUMN_NEVER_EXPIRES             => $neverExpires,
				SubscriptionRepository::COLUMN_NEXT_BILLING_AMOUNT       => $nextBillingAmount,
				SubscriptionRepository::COLUMN_PAYMENT_METHOD_TOKEN      => $paymentMethodToken,
			]
		);
	}

	private function createBraintreeSubs(
		string $id,
		\DateTime $createdAt,
		\DateTime $updatedAt,
		\DateTime $billingPeriodStartDate,
		\DateTime $billingPeriodEndDate,
		\DateTime $firstBillingDate,
		\DateTime $nextBillingDate,
		?\DateTime $paidThroughDate = null,
		string $planId = '',
		string $price = '',
		string $status = '',
		string $merchantAccountId = '',
		bool $neverExpires = true,
		string $nextBillingPeriodAmount = '',
		string $paymentMethodToken = ''
	): \Braintree\Subscription {
		$attributes = [
			'id'                      => $id,
			'planId'                  => $planId,
			'createdAt'               => $createdAt,
			'updatedAt'               => $updatedAt,
			'price'                   => $price,
			'status'                  => $status,
			'billingPeriodStartDate'  => $billingPeriodStartDate,
			'billingPeriodEndDate'    => $billingPeriodEndDate,
			'firstBillingDate'        => $firstBillingDate,
			'nextBillingDate'         => $nextBillingDate,
			'paidThroughDate'         => $paidThroughDate,
			'merchantAccountId'       => $merchantAccountId,
			'neverExpires'            => $neverExpires,
			'nextBillingPeriodAmount' => $nextBillingPeriodAmount,
			'paymentMethodToken'      => $paymentMethodToken,
		];

		return \Braintree\Subscription::factory($attributes);
	}
}