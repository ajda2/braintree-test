<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Subscription;


use Mrcek\BraintreeTest\Repository\AbstractRepository;
use Mrcek\BraintreeTest\Repository\IdentifyEntity;
use Nette\Database\Table\ActiveRow;
use Ramsey\Uuid\Uuid;


/**
 * @extends  AbstractRepository<Subscription>
 */
class SubscriptionRepository extends AbstractRepository {

	public const TABLE_NAME = 'subscription';

	/** @var string */
	public const
		COLUMN_ID = 'id',
		COLUMN_UUID = 'uuid',
		COLUMN_BRAINTREE_ID = 'braintree_id',
		COLUMN_BRAINTREE_PLAN_ID = 'braintree_plan_id',
		COLUMN_BRAINTREE_CUSTOMER_ID = 'braintree_customer_id',
		COLUMN_CREATED_AT = 'created_at',
		COLUMN_UPDATED_AT = 'updated_at',
		COLUMN_PRICE = 'price',
		COLUMN_CURRENCY_ISO = 'currency_iso',
		COLUMN_STATUS = 'status',
		COLUMN_BILLING_PERIOD_START_DATE = 'billing_period_start_date',
		COLUMN_BILLING_PERIOD_END_DATE = 'billing_period_end_date',
		COLUMN_FIRST_BILLING_DATE = 'first_billing_date',
		COLUMN_NEXT_BILLING_DATE = 'next_billing_date',
		COLUMN_PAID_THROUGH_DATE = 'paid_through_date',
		COLUMN_MERCHANT_ACCOUNT_ID = 'merchant_account_id',
		COLUMN_NEVER_EXPIRES = 'never_expires',
		COLUMN_NEXT_BILLING_AMOUNT = 'next_billing_period_amount',
		COLUMN_PAYMENT_METHOD_TOKEN = 'payment_method_token';

	/**
	 * @return array<mixed, mixed>
	 */
	protected function createInsertData(IdentifyEntity $entity): array {
		return [
			$this::COLUMN_ID                        => $entity->getId(),
			$this::COLUMN_UUID                      => $entity->getUuid(),
			$this::COLUMN_BRAINTREE_ID              => $entity->getBraintreeId(),
			$this::COLUMN_BRAINTREE_PLAN_ID         => $entity->getBraintreePlanId(),
			$this::COLUMN_BRAINTREE_CUSTOMER_ID     => $entity->getBraintreeCustomerId(),
			$this::COLUMN_CREATED_AT                => $entity->getCreatedAt(),
			$this::COLUMN_UPDATED_AT                => $entity->getUpdatedAt(),
			$this::COLUMN_PRICE                     => $entity->getPrice(),
			$this::COLUMN_CURRENCY_ISO              => $entity->getCurrencyIso(),
			$this::COLUMN_STATUS                    => $entity->getStatus(),
			$this::COLUMN_BILLING_PERIOD_START_DATE => $entity->getBillingPeriodStartAt(),
			$this::COLUMN_BILLING_PERIOD_END_DATE   => $entity->getBillingPeriodEndAt(),
			$this::COLUMN_FIRST_BILLING_DATE        => $entity->getFirstBillingAt(),
			$this::COLUMN_NEXT_BILLING_DATE         => $entity->getNextBillingAt(),
			$this::COLUMN_PAID_THROUGH_DATE         => $entity->getPaidThroughDate(),
			$this::COLUMN_MERCHANT_ACCOUNT_ID       => $entity->getBraintreeMerchantAccountId(),
			$this::COLUMN_NEVER_EXPIRES             => $entity->isNeverExpires(),
			$this::COLUMN_NEXT_BILLING_AMOUNT       => $entity->getNextBillingPeriodAmount(),
			$this::COLUMN_PAYMENT_METHOD_TOKEN      => $entity->getPaymentMethodToken(),
		];
	}

	/**
	 * @return array<mixed, mixed>
	 */
	protected function createUpdateData(IdentifyEntity $entity): array {
		return $this->createInsertData($entity);
	}

	protected function fromRowFactory(ActiveRow $row): IdentifyEntity {
		return new Subscription(
			(int)$row->offsetGet($this::COLUMN_ID),
			Uuid::fromString($row->offsetGet($this::COLUMN_UUID)),
			$row->offsetGet($this::COLUMN_BRAINTREE_ID),
			$row->offsetGet($this::COLUMN_BRAINTREE_PLAN_ID),
			$row->offsetGet($this::COLUMN_BRAINTREE_CUSTOMER_ID),
			$row->offsetGet($this::COLUMN_CREATED_AT),
			$row->offsetGet($this::COLUMN_UPDATED_AT),
			$row->offsetGet($this::COLUMN_PRICE),
			$row->offsetGet($this::COLUMN_CURRENCY_ISO),
			$row->offsetGet($this::COLUMN_STATUS),
			$row->offsetGet($this::COLUMN_BILLING_PERIOD_START_DATE),
			$row->offsetGet($this::COLUMN_BILLING_PERIOD_END_DATE),
			$row->offsetGet($this::COLUMN_FIRST_BILLING_DATE),
			$row->offsetGet($this::COLUMN_NEXT_BILLING_DATE),
			$row->offsetGet($this::COLUMN_PAID_THROUGH_DATE),
			$row->offsetGet($this::COLUMN_MERCHANT_ACCOUNT_ID),
			(bool)$row->offsetGet($this::COLUMN_NEVER_EXPIRES),
			$row->offsetGet($this::COLUMN_NEXT_BILLING_AMOUNT),
			$row->offsetGet($this::COLUMN_PAYMENT_METHOD_TOKEN)
		);
	}
}