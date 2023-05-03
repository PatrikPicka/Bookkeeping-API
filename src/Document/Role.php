<?php

declare(strict_types = 1);

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Query;
use App\Document\Trait\ActiveTrait;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\IdTrait;
use App\Document\Trait\NameTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	security: "is_granted('ROLE_API')",
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

	public function __toString(): string
	{
		return $this->getName();
	}
}