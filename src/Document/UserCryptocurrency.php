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
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new QueryCollection(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new DeleteMutation(security: "is_granted(['ROLE_SUPER_ADMIN', 'ROLE_USER_ADMIN'])or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'delete'),
		new Mutation(security: "is_granted(['ROLE_SUPER_ADMIN', 'ROLE_USER_ADMIN'])or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'create'),
		new Mutation(security: "is_granted(['ROLE_SUPER_ADMIN', 'ROLE_USER_ADMIN']) or (is_granted('ROLE_USER') and object.user.id == user)", name: 'update'),
	],
)]
#[ODM\Document]
final class UserCryptocurrency
{
	use IdTrait;
	use CUDTrait;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: 'false')]
	protected string $cryptocurrencyIdentifier;

	#[ApiProperty(writable: false)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: User::class)]
	protected User $user;

	public function getCryptoIdentifier(): string
	{
		return $this->cryptoIdentifier;
	}

	public function setCryptoIdentifier(string $cryptoIdentifier): void
	{
		$this->cryptoIdentifier = $cryptoIdentifier;
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