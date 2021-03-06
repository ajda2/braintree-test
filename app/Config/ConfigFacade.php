<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Config;


use Mrcek\BraintreeTest\Repository\Exceptions\NotFoundException;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

class ConfigFacade {

	use SmartObject;

	private ConfigRepository $repository;

	/** @var ArrayHash<string, Value> */
	private ArrayHash $map;

	public function __construct(ConfigRepository $repository) {
		$this->repository = $repository;
		$this->map = new ArrayHash();

		$this->preload();
	}

	/**
	 * @throws NotFoundException
	 */
	public function getValue(Code $code): Value {
		$_code = $code->getValue();
		$value = $this->findValue($_code);

		if (!$value instanceof Value) {
			throw new NotFoundException("Value with code '{$_code}' was not found");
		}

		return $value;
	}

	public function findValue(string $code): ?Value {
		if ($this->map->offsetExists($code)) {
			return $this->map->offsetGet($code);
		}

		$value = $this->repository->findOneBy([$this->repository::COLUMN_CODE => $code]);

		if ($value instanceof Value) {
			$this->map->offsetSet($code, $value);
		}

		return $value;
	}

	private function preload(): void {
		foreach ($this->repository->findBy([]) as $value) {
			$this->map->offsetSet($value->getCode(), $value);
		}
	}
}