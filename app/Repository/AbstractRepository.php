<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Repository;


use Mrcek\BraintreeTest\Repository\Exceptions\InsertException;
use Mrcek\BraintreeTest\Repository\Exceptions\UpdateException;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Utils\ArrayList;
use Tracy\ILogger;

/**
 * @template T of IdentifyEntity
 */
abstract class AbstractRepository {

	use SmartObject;

	protected const TABLE_NAME = '';

	protected Explorer $database;

	protected ILogger $logger;

	public function __construct(Explorer $database, ILogger $logger) {
		$this->database = $database;
		$this->logger = $logger;
	}

	/**
	 * @param T $entity
	 * @return T
	 * @throws InsertException
	 * @throws UpdateException
	 */
	public function save(IdentifyEntity $entity): IdentifyEntity {
		try {
			if ($entity->getId() === 0) {
				$row = $this->insert($this->createInsertData($entity));

				return $this->fromRowFactory($row);
			}

			$this->update($this->createUpdateData($entity));

			return $entity;
		} catch (\Throwable $e) {
			$this->logger->log($e, $this->logger::ERROR);

			throw $e;
		}
	}

	/**
	 * @param int $id
	 * @return T|null
	 */
	public function get(int $id): ?IdentifyEntity {
		$data = ['id' => $id];

		$row = $this->getTable()->where($data)->limit(1)->fetch();

		if (!$row instanceof ActiveRow) {
			return null;
		}

		return $this->fromRowFactory($row);
	}

	public function delete(int $id): bool {
		$data = ['id' => $id];

		try {
			$this->getTable()->where($data)->limit(1)->delete();
		} catch (\Throwable $e) {
			$this->logger->log($e, $this->logger::ERROR);

			return false;
		}

		return true;
	}

	/**
	 * @param array<string, mixed> $by
	 * @param string|null          $order
	 * @param int<0, max>|null     $limit
	 * @param int<0, max>|null     $offset
	 * @return ArrayList|T[]
	 */
	public function findBy(array $by, ?string $order = null, ?int $limit = null, ?int $offset = null): ArrayList {
		$result = new ArrayList();
		$selection = $this->getTable()->where($by);
		if ($order !== null) {
			$selection->order($order);
		}

		foreach ($selection->limit($limit, $offset) as $row) {
			$result[] = $this->fromRowFactory($row);
		}

		return $result;
	}

	/**
	 * @param array<string, mixed> $by
	 * @return T|null
	 */
	public function findOneBy(array $by): ?IdentifyEntity {
		$result = $this->findBy($by, null, 1);

		if ($result->count() < 1) {
			return null;
		}

		return $result->offsetGet(0);
	}

	/**
	 * @param array<string, mixed> $data
	 * @throws InsertException
	 */
	protected function insert(array $data): ActiveRow {
		try {
			$result = $this->getTable()->insert($data);
		} catch (\Throwable $e) {
			throw new InsertException();
		}

		if (!$result instanceof ActiveRow) {
			throw new InsertException();
		}

		return $result;
	}

	/**
	 * @param array<string, mixed> $data
	 * @throws UpdateException
	 */
	protected function update(array $data): void {
		try {
			$this->getTable()->update($data);
		} catch (\Throwable $e) {
			throw new UpdateException();
		}
	}

	protected function getTable(): Selection {
		return $this->database->table($this::TABLE_NAME);
	}

	/**
	 * @param T $entity
	 * @return array<string, mixed>
	 */
	abstract protected function createInsertData(IdentifyEntity $entity): array;

	/**
	 * @param T $entity
	 * @return array<string, mixed>
	 */
	abstract protected function createUpdateData(IdentifyEntity $entity): array;

	/**
	 * @param ActiveRow $row
	 * @return T
	 */
	abstract protected function fromRowFactory(ActiveRow $row): IdentifyEntity;
}