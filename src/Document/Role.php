<?php

declare(strict_types = 1);

namespace App\Document;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Query;
use App\Document\Trait\ActiveTrait;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\IdTrait;
use App\Document\Trait\NameTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(),
	],
)]
#[ODM\Document]
class Role implements DocumentInterface
{
	use IdTrait;
	use NameTrait;
	use ActiveTrait;
	use CUDTrait;
}