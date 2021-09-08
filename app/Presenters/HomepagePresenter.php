<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Presenters;


use Braintree\Gateway;

final class HomepagePresenter extends BasePresenter {

	/** @var Gateway @inject */
	public Gateway $braintTreeGateway;
	
	public function renderDefault(): void {
		$this->template->anyVariable = 'any value';
	}
}