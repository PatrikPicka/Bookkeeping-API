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
class UserAccount implements DocumentInterface
{
	use IdTrait;
	use NameTrait;
	use CUDTrait;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'float', nullable: false, options: ['default' => 0])]
	protected float $balance;

	#[ApiProperty(writable: true)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: User::class)]
	protected User $user;

	public function getBalance(): float
	{
		return $this->balance;
	}

	public function setBalance(float $balance): void
	{
		$this->balance = $balance;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}
}