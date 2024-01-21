<?php

namespace App\Document;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Document\Trait\IdTrait;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\ActiveTrait;
use App\Document\Trait\NameTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(security: "(is_granted('ROLE_SUPER_ADMIN')"),
		new QueryCollection(security: "(is_granted('ROLE_SUPER_ADMIN')"),
		new DeleteMutation(security: "is_granted('ROLE_SUPER_ADMIN')", name: 'delete'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN')", name: 'create'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN')", name: 'update'),
	],
)]
#[ODM\Document]
class Currency implements DocumentInterface
{
	use IdTrait;
	use CUDTrait;
	use ActiveTrait;
	use NameTrait;

	#[ApiProperty(readable: true, writable: true)]
	#[ODM\Field(type: 'string', nullable: false)]
	#[Assert\NotBlank]
	protected string $code;

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode(string $code): void
	{
		$this->code = $code;
	}
}