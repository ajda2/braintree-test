<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Config;


use Mrcek\BraintreeTest\Repository\AbstractRepository;
use Mrcek\BraintreeTest\Repository\IdentifyEntity;
use Nette\Database\Table\ActiveRow;


/**
 * @extends  AbstractRepository<Value>
 */
class ConfigRepository extends AbstractRepository {

	public const TABLE_NAME = 'config';

	/** @var string */
	public const COLUMN_ID = 'id';

	/** @var string */
	public const COLUMN_CODE = 'code';

	/** @var string */
	public const COLUMN_VALUE = 'value';

	/**
	 * @return array<string, mixed>
	 */
	protected function createInsertData(IdentifyEntity $entity): array {
		return [
			$this::COLUMN_CODE  => $entity->getCode(),
			$this::COLUMN_VALUE => $entity->getValue(),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	protected function createUpdateData(IdentifyEntity $entity): array {
		return [
			$this::COLUMN_VALUE => $entity->getValue(),
		];
	}

	protected function fromRowFactory(ActiveRow $row): IdentifyEntity {
		return new Value(
			(int)$row->offsetGet($this::COLUMN_ID),
			$row->offsetGet($this::COLUMN_CODE),
			$row->offsetGet($this::COLUMN_VALUE),
		);
	}
}