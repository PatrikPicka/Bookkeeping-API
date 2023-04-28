<?php

namespace App\Stage;

use ApiPlatform\GraphQl\Resolver\Stage\WriteStageInterface;
use ApiPlatform\Metadata\GraphQl\Operation;
use App\Document\DocumentInterface;

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

		return ($this->writeStage)($data, $resourceClass, $operation, $context);
	}
}