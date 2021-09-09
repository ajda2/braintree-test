<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Braintree\Components\PlanSelect;


interface PlanSelectControlFactory {

	public function create(): PlanSelectControl;
}