<?php

namespace App\Stage;

use ApiPlatform\GraphQl\Resolver\Stage\WriteStageInterface;
use ApiPlatform\Metadata\GraphQl\Operation;
use App\Document\DocumentInterface;
use DateTimeImmutable;

class WriteStage implements WriteStageInterface
{
	public const OPERATION_NAME_CREATE = 'create';
	public const OPERATION_NAME_UPDATE = 'update';
	public const OPERATION_NAME_DELETE = 'delete';

	public function __construct(
		private readonly WriteStageInterface $writeStage,
	)
	{
	}

	public function __invoke(?object $data, string $resourceClass, Operation $operation, array $context): ?object
	{
		assert($data instanceof DocumentInterface);

		switch ($operation->getName()) {
			case self::OPERATION_NAME_CREATE:
				$data->setCreatedAt(new DateTimeImmutable());
				$data->setUpdatedAt(new DateTimeImmutable());
				break;

			case self::OPERATION_NAME_UPDATE:
				$data->setUpdatedAt(new DateTimeImmutable());
				break;

			case self::OPERATION_NAME_DELETE:
				$data->setDeletedAt(new DateTimeImmutable());
				break;
		}

		return ($this->writeStage)($data, $resourceClass, $operation, $context);
	}
}