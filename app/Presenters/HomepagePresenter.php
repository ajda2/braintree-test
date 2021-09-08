<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Presenters;


use Braintree\Gateway;
use Braintree\Plan;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\ArrayHash;

final class HomepagePresenter extends BasePresenter {

	/** @var Gateway @inject */
	public Gateway $brainTreeGateway;

	public function actionCustomer(string $planId): void {
		// TODO: Load plan from API
	}

	public function actionPayment(string $planId): void {
		// TODO: Load plan from API
	}

	public function renderDefault(): void {
		/** @var Template $template */
		$template = $this->getTemplate();

		$template->braintreeCLientToken = $this->brainTreeGateway->clientToken()->generate();
		$template->formId = $this->getComponent('paymentForm')->getElementPrototype()->getAttribute('id');
		$template->braintreePlans = $this->getAllPlans();

		$this->flashMessage('Test error', FlashMessageType::ERROR);
	}

	protected function createComponentPaymentForm(): Form {
		$form = new Form();
		$form->addRadioList('plan', 'Plán', ['hv5b' => 'Plán PROFI']);
		$form->addHidden('payment_method_nonce')->setHtmlId('nonce');
		$form->addSubmit('send');

		$form->onSuccess[] = function (Form $form, ArrayHash $values): void {
			$customerData = [
				'firstName'          => 'Mike',
				'lastName'           => 'Jones',
				'email'              => 'new.email@example.com',
				'company'            => 'Jones Co.',
				'paymentMethodNonce' => $values->offsetGet('payment_method_nonce')
			];

			$result = $this->brainTreeGateway->customer()->create($customerData);

			if (!$result->success) {
				//				bdump($result->errors->deepAll());

				return;
			}

			$subscriptionData = [
				'paymentMethodToken' => $result->customer->paymentMethods[0]->token,
				'planId'             => $values->offsetGet('plan'),
			];

			//			echo($result->customer->id);

			$result = $this->brainTreeGateway->subscription()->create($subscriptionData);
			//			bdump($result);
		};

		return $form;
	}

	/**
	 * @return array|Plan[]
	 */
	private function getAllPlans(): array {
		return $this->brainTreeGateway->plan()->all();
	}

	/**
	 * @throws BadRequestException
	 */
	private function getPlan(string $id): Plan {
		/** @var Plan $plan */
		foreach ($this->getAllPlans() as $plan) {
			if ($plan->id === $id) {
				return $plan;
			}
		}

		throw new BadRequestException("Plan with id '{$id}' not found");
	}
}