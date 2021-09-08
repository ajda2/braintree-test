<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Facade;


use Mrcek\BraintreeTest\Repository\IdentifyEntity;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\ArrayList;
use Traversable;

/**
 * @template T of IdentifyEntity
 * @implements \IteratorAggregate<T>
 */
abstract class AbstractIdentityMap implements \Countable, \IteratorAggregate {

	use SmartObject;

	/**
	 * @var ArrayHash<int, T>
	 */
	protected ArrayHash $map;

	public function __construct() {
		$this->map = new ArrayHash();
	}

	/**
	 * @param T $entity
	 * @return $this
	 */
	public function add(IdentifyEntity $entity): self {
		if ($this->map->offsetExists($entity->getId())) {
			return $this;
		}

		$this->map->offsetSet($entity->getId(), $entity);

		return $this;
	}

	/**
	 * @param int $id
	 * @return T|null
	 */
	public function get(int $id): ?IdentifyEntity {
		if ($this->map->offsetExists($id)) {
			return $this->map->offsetGet($id);
		}

		return null;
	}

	/**
	 * @param int $id
	 * @return $this<T>
	 */
	public function remove(int $id): self {
		if ($this->map->offsetExists($id)) {
			$this->map->offsetUnset($id);
		}

		return $this;
	}

	/**
	 * @return ArrayList<T>
	 */
	public function getAll(): ArrayList {
		$list = new ArrayList();

		foreach ($this->map as $item) {
			$list[] = $item;
		}

		return $list;
	}

	public function getIterator(): Traversable {
		return $this->map->getIterator();
	}

	public function count(): int {
		return $this->map->count();
	}
}