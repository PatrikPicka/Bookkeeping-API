<?php

declare(strict_types = 1);

namespace App\Document;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Query;
use App\Document\Trait\ActiveTrait;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\EmailTrait;
use App\Document\Trait\IdTrait;
use App\Document\Trait\NameTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
	security: "is_granted('ROLE_API')",
	graphQlOperations: [
		new Query(),
	],
)]
#[ODM\Document(repositoryClass: UserRepository::class)]
class User implements DocumentInterface, UserInterface
{
	use IdTrait;
	use CUDTrait;
	use ActiveTrait;
	use EmailTrait;
	use NameTrait;

	#[ApiProperty(writable: false)]
	#[ODM\Field(type: 'int', nullable: false)]
	#[Assert\NotBlank]
	#[Assert\Unique]
	protected string|null $authId;

	#[ODM\EmbedMany(nullable: false, targetDocument: ApiToken::class)]
	protected Collection $tokens;

	#[ApiProperty(writable: true)]
	#[ODM\EmbedMany(targetDocument: Role::class)]
	protected Collection $roles;

	public function __construct()
	{
		$this->tokens = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}

	public function getAuthId(): ?string
	{
		return $this->authId;
	}

	public function setAuthId(?string $authId): User
	{
		$this->authId = $authId;

		return $this;
	}

	public function getTokens(): Collection
	{
		return $this->tokens;
	}

	public function setTokens(Collection $tokens): void
	{
		$this->tokens = $tokens;
	}

	public function addToken(ApiToken $token): void
	{
		$this->tokens->add($token);
	}

	public function setRoles(Collection $roles): void
	{
		$this->roles = $roles;
	}

	public function getRoles(): array
	{
		$roles = ['ROLE_USER'];

		return array_merge($roles, array_map(function (Role $role): string {
			return $role->getName();
		}, $this->roles->toArray()));
	}

	public function addRole(Role $role): User
	{
		$this->roles->add($role);

		return $this;
	}

	public function eraseCredentials(): void
	{
		$this->setName('---');
		$this->setEmail('---');
	}

	public function getUserIdentifier(): string
	{
		if ($this->id === null) {
			throw new UnsupportedUserException(message: 'There was an error while trying to access your identifier.');
		}

		return $this->id;
	}
}