<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Presenters;


use Braintree\Gateway;
use Braintree\Plan;
use Mrcek\BraintreeTest\Braintree\Components\PlanSelect\PlanSelectControl;
use Mrcek\BraintreeTest\Braintree\Components\PlanSelect\PlanSelectControlFactory;
use Mrcek\BraintreeTest\Braintree\Components\SubscriptionCheckout\SubscriptionCheckoutControl;
use Mrcek\BraintreeTest\Braintree\Components\SubscriptionCheckout\SubscriptionCheckoutControlFactory;
use Mrcek\BraintreeTest\Braintree\Subscription\Subscription;
use Mrcek\BraintreeTest\Braintree\Subscription\SubscriptionFacade;
use Nette\Application\BadRequestException;
use Nette\Bridges\ApplicationLatte\Template;
use Ramsey\Uuid\Uuid;

final class HomepagePresenter extends BasePresenter {

	/** @var Gateway @inject */
	public Gateway $brainTreeGateway;

	/** @var SubscriptionFacade @inject */
	public SubscriptionFacade $subscriptionFacade;

	/** @var PlanSelectControlFactory @inject */
	public PlanSelectControlFactory $planSelectControlFactory;

	/** @var SubscriptionCheckoutControlFactory @inject */
	public SubscriptionCheckoutControlFactory $subscriptionCheckoutControlFactory;

	private Plan $plan;

	/**
	 * @param string $id
	 * @throws BadRequestException
	 */
	public function actionCheckout(string $id): void {
		$plan = $this->getPlan($id);

		if ($plan === null) {
			throw new BadRequestException("Plan with id '{$id}' not found");
		}

		$this->plan = $plan;
	}

	/**
	 * @param string $id
	 * @throws BadRequestException
	 */
	public function renderResult(string $id): void {
		$subscription = $this->subscriptionFacade->findByUuid(Uuid::fromString($id));
		if (!$subscription instanceof Subscription) {
			throw new BadRequestException("Subscription with uuid '{$id}' not found");
		}

		/** @var Template $template */
		$template = $this->getTemplate();

		$template->subscription = $subscription;
	}

	protected function createComponentPlanSelect(): PlanSelectControl {
		$control = $this->planSelectControlFactory->create();

		$control->onSelect[] = function (PlanSelectControl $sender, string $planId): void {
			$this->redirect(':Homepage:checkout', ['id' => $planId]);
		};

		return $control;
	}

	protected function createComponentCheckout(): SubscriptionCheckoutControl {
		$control = $this->subscriptionCheckoutControlFactory->create($this->plan, 'dropin-container');

		$control->onCheckout[] = function (SubscriptionCheckoutControl $sender, Subscription $subscription): void {
			$this->flashMessage('page.checkout.flash.subscription.success', FlashMessageType::SUCCESS);
			$this->redirect(':Homepage:result', ['id' => $subscription->getUuid()->toString()]);
		};

		return $control;
	}

	private function getPlan(string $id): ?Plan {
		/** @var Plan $plan */
		foreach ($this->brainTreeGateway->plan()->all() as $plan) {
			if ($plan->id === $id) {
				return $plan;
			}
		}

		return null;
	}
}