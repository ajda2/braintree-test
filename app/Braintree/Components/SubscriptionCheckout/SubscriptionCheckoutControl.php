<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Components\SubscriptionCheckout;


use Braintree\Error\Validation;
use Braintree\Gateway;
use Braintree\Plan;
use Braintree\Result\Error;
use Closure;
use Mrcek\BraintreeTest\Braintree\Subscription\Subscription;
use Mrcek\BraintreeTest\Braintree\Subscription\SubscriptionFacade;
use Mrcek\BraintreeTest\Presenters\FlashMessageType;
use Mrcek\BraintreeTest\Repository\Exceptions\PersistException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * @method onCheckout(SubscriptionCheckoutControl $sender, Subscription $subscription)
 */
class SubscriptionCheckoutControl extends Control {

	/** @var array|callable[]|Closure[] */
	public array $onCheckout = [];

	private Gateway $gateway;

	private Plan $plan;

	private SubscriptionFacade $subscriptionFacade;

	private string $paymentElemId;

	public function __construct(
		Plan $plan,
		string $paymentElemId,
		Gateway $gateway,
		SubscriptionFacade $subscriptionFacade
	) {
		$this->plan = $plan;
		$this->gateway = $gateway;
		$this->subscriptionFacade = $subscriptionFacade;
		$this->paymentElemId = $paymentElemId;
	}

	public function render(): void {
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/subscriptionCheckoutControl.latte');

		$template->paymentElemId = $this->paymentElemId;

		$template->render();
	}

	public function handleGetClientToken(): void {
		$presenter = $this->getPresenter();

		if (!$presenter->isAjax()) {
			return;
		}

		$presenter->payload->braintree_client_token = $this->gateway->clientToken()->generate();
		$presenter->sendPayload();
	}

	public function onFormSubmit(Form $form, ArrayHash $values): void {
		$customerData = [
			'firstName'          => $values->offsetGet('firstName'),
			'lastName'           => $values->offsetGet('lastName'),
			'email'              => $values->offsetGet('email'),
			'company'            => $values->offsetGet('company'),
			'paymentMethodNonce' => $values->offsetGet('payment_method_nonce')
		];

		$customerResult = $this->gateway->customer()->create($customerData);

		if ($customerResult instanceof Error) {
			/** @var Validation $_error */
			foreach ($customerResult->errors->deepAll() as $_error) {
				$this->flashMessage(\sprintf("%s: %s", $_error->code, $_error->message), FlashMessageType::ERROR);
			}

			return;
		}

		$subscriptionData = [
			'paymentMethodToken' => $customerResult->customer->paymentMethods[0]->token,
			'planId'             => $this->plan->id,
		];

		$result = $this->gateway->subscription()->create($subscriptionData);

		if($result instanceof Error){
			/** @var Validation $_error */
			foreach ($result->errors->deepAll() as $_error) {
				$this->flashMessage(\sprintf("%s: %s", $_error->code, $_error->message), FlashMessageType::ERROR);
			}

			return;
		}

		try {
			$subscription = $this->subscriptionFacade->saveBraintreeSubscription(
				$result->subscription,
				$customerResult->customer->id,
				$this->plan->currencyIsoCode
			);

			$form->reset();
			$this->onCheckout($this, $subscription);
		} catch (PersistException $e) {
			$this->flashMessage('component.subscription_checkout.flash.error', FlashMessageType::ERROR);
		}
	}

	protected function createComponentCheckoutForm(): Form {
		$form = new Form();
		$form->getElementPrototype()->setAttribute('id', 'checkout-form');

		$form->addHidden('payment_method_nonce')->setHtmlId('nonce');
		$form->addText('email', 'component.subscription_checkout.form.email.label')
			->setRequired()
			->addRule(\Nette\Forms\Form::EMAIL, 'component.subscription_checkout.form.email.error.invalid_type');
		$form->addText('firstName', 'component.subscription_checkout.form.first_name.label')
			->setRequired();
		$form->addText('lastName', 'component.subscription_checkout.form.last_name.label')
			->setRequired();
		$form->addText('company', 'component.subscription_checkout.form.company.label')
			->setRequired(false);
		$form->addSubmit('send');

		$form->onSuccess[] = [
			$this,
			'onFormSubmit'
		];

		return $form;
	}
}