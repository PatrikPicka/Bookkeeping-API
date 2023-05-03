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
	protected int|null $authId;

	#[ApiProperty(writable: true)]
	#[ODM\ReferenceMany(storeAs: "id", targetDocument: Role::class)]
	protected Collection $userRoles;

	public function __construct()
	{
		$this->userRoles = new ArrayCollection();
	}

	public function getAuthId(): ?int
	{
		return $this->authId;
	}

	public function setAuthId(?int $authId): User
	{
		$this->authId = $authId;

		return $this;
	}

	public function getRoles(): array
	{
		return array_map(function (Role $role): string {
			return $role->getName();
		}, $this->userRoles->toArray());
	}

	public function getUserRoles(): Collection
	{
		return $this->userRoles;
	}

	public function setUserRoles(Collection $roles): void
	{
		$this->userRoles = $roles;
	}

	public function addUserRole(Role $role): User
	{
		$this->userRoles->add($role);

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