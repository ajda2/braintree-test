<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Components\SubscriptionCheckout;


use Braintree\Plan;

interface SubscriptionCheckoutControlFactory {

	public function create(Plan $plan, string $paymentElemId): SubscriptionCheckoutControl;
}