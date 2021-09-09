<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Components\PlanSelect;


use Braintree\Gateway;
use Closure;
use Nette\Application\UI\Control;

/**
 * @method onSelect(PlanSelectControl $sender, string $planId)
 */
class PlanSelectControl extends Control {

	/** @var array|callable[]|Closure[] */
	public array $onSelect = [];

	private Gateway $gateway;

	public function __construct(Gateway $gateway) {
		$this->gateway = $gateway;
	}

	public function render(): void {
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/planSelectControl.latte');

		$template->plans = $this->gateway->plan()->all();

		$template->render();
	}

	public function handleSelect(string $planId): void {
		$this->onSelect($this, $planId);
	}
}