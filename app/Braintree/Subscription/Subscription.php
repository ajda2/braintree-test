<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Subscription;


use Mrcek\BraintreeTest\Repository\IdentifyEntity;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Ramsey\Uuid\UuidInterface;

class Subscription implements IdentifyEntity {

	use SmartObject;

	private int $id;

	private UuidInterface $uuid;

	private string $braintreeId;

	private string $braintreePlanId;

	private DateTime $createdAt;

	private DateTime $updatedAt;

	private string $price;

	private string $currencyIso;

	private string $status;

	private DateTime $billingPeriodStartAt;

	private DateTime $billingPeriodEndAt;

	private DateTime $firstBillingAt;

	private ?DateTime $nextBillingAt;

	private ?DateTime $paidThroughDate;

	private string $braintreeMerchantAccountId;

	private bool $neverExpires;

	private string $nextBillingPeriodAmount;

	private string $paymentMethodToken;

	private string $braintreeCustomerId;

	public function __construct(
		int $id,
		UuidInterface $uuid,
		string $braintreeId,
		string $braintreePlanId,
		string $braintreeCustomerId,
		DateTime $createdAt,
		DateTime $updatedAt,
		string $price,
		string $currencyIso,
		string $status,
		DateTime $billingPeriodStartAt,
		DateTime $billingPeriodEndAt,
		DateTime $firstBillingAt,
		?DateTime $nextBillingAt,
		?DateTime $paidThroughDate,
		string $braintreeMerchantAccountId,
		bool $neverExpires,
		string $nextBillingPeriodAmount,
		string $paymentMethodToken
	) {
		$this->id = $id;
		$this->uuid = $uuid;
		$this->braintreeId = $braintreeId;
		$this->braintreePlanId = $braintreePlanId;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
		$this->price = $price;
		$this->currencyIso = $currencyIso;
		$this->status = $status;
		$this->billingPeriodStartAt = $billingPeriodStartAt;
		$this->billingPeriodEndAt = $billingPeriodEndAt;
		$this->firstBillingAt = $firstBillingAt;
		$this->nextBillingAt = $nextBillingAt;
		$this->paidThroughDate = $paidThroughDate;
		$this->braintreeMerchantAccountId = $braintreeMerchantAccountId;
		$this->neverExpires = $neverExpires;
		$this->nextBillingPeriodAmount = $nextBillingPeriodAmount;
		$this->paymentMethodToken = $paymentMethodToken;
		$this->braintreeCustomerId = $braintreeCustomerId;
	}

	public function getId(): int {
		return $this->id;
	}

	public function getUuid(): UuidInterface {
		return $this->uuid;
	}

	public function getBraintreeId(): string {
		return $this->braintreeId;
	}

	public function getBraintreePlanId(): string {
		return $this->braintreePlanId;
	}

	public function getCreatedAt(): DateTime {
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTime {
		return $this->updatedAt;
	}

	public function getPrice(): string {
		return $this->price;
	}

	public function getCurrencyIso(): string {
		return $this->currencyIso;
	}

	public function getStatus(): string {
		return $this->status;
	}

	public function getBillingPeriodStartAt(): DateTime {
		return $this->billingPeriodStartAt;
	}

	public function getBillingPeriodEndAt(): DateTime {
		return $this->billingPeriodEndAt;
	}

	public function getFirstBillingAt(): DateTime {
		return $this->firstBillingAt;
	}

	public function getNextBillingAt(): ?DateTime {
		return $this->nextBillingAt;
	}

	public function getPaidThroughDate(): ?DateTime {
		return $this->paidThroughDate;
	}

	public function getBraintreeMerchantAccountId(): string {
		return $this->braintreeMerchantAccountId;
	}

	public function isNeverExpires(): bool {
		return $this->neverExpires;
	}

	public function getNextBillingPeriodAmount(): string {
		return $this->nextBillingPeriodAmount;
	}

	public function getPaymentMethodToken(): string {
		return $this->paymentMethodToken;
	}

	public function getBraintreeCustomerId(): string {
		return $this->braintreeCustomerId;
	}

	/**
	 * @throws \Exception
	 */
	public static function fromBraintree(
		\Braintree\Subscription $subscription,
		string $braintreeCustomerId,
		UuidInterface $uuid,
		string $currencyIso
	): self {
		return new self(
			0,
			$uuid,
			$subscription->id,
			$subscription->planId,
			$braintreeCustomerId,
			DateTime::from($subscription->createdAt),
			DateTime::from($subscription->updatedAt),
			$subscription->price,
			$currencyIso,
			$subscription->status,
			DateTime::from($subscription->billingPeriodStartDate),
			DateTime::from($subscription->billingPeriodEndDate),
			DateTime::from($subscription->firstBillingDate),
			DateTime::from($subscription->nextBillingDate),
			$subscription->paidThroughDate !== null ? DateTime::from($subscription->paidThroughDate) : null,
			$subscription->merchantAccountId,
			$subscription->neverExpires,
			$subscription->nextBillingPeriodAmount,
			$subscription->paymentMethodToken
		);
	}
}