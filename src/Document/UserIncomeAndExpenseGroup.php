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
use App\Document\Trait\CUDTrait;
use App\Document\Trait\IdTrait;
use App\Document\Trait\NameTrait;
use App\Enum\IncomeAndExpenseTypeEnum;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new QueryCollection(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new DeleteMutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'delete'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'create'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'update'),
	],
)]
#[ODM\Document]
#[ODM\InheritanceType('COLLECTION_PER_CLASS')]
class UserIncomeAndExpenseGroup implements DocumentInterface
{
	use IdTrait;
	use NameTrait;
	use CUDTrait;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: false, options: ['default' => '#ffffff'])]
	protected string $color;

	#[ApiProperty(writable: true)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: User::class)]
	protected User $user;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: false, enumType: IncomeAndExpenseTypeEnum::class)]
	protected IncomeAndExpenseTypeEnum $type;

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function setColor(string $color): void
	{
		$this->color = $color;
	}

	public function getType(): IncomeAndExpenseTypeEnum
	{
		return $this->type;
	}

	public function setType(IncomeAndExpenseTypeEnum $type): void
	{
		$this->type = $type;
	}
}