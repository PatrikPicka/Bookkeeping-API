<?php

declare(strict_types = 1);

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Query;
use App\Document\Trait\ActiveTrait;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\EmailTrait;
use App\Document\Trait\IdTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
	graphQlOperations: [
		new Query(),
	],
)]
class User implements DocumentInterface, UserInterface
{
	use IdTrait;
	use CUDTrait;
	use ActiveTrait;
	use EmailTrait;

	#[ODM\Field(type: 'int', nullable: false)]
	#[Assert\NotBlank]
	protected string|null $authId;

	#[ODM\Field(type: 'date_immutable', nullable: false)]
	#[Assert\NotBlank]
	protected string $name;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): User
	{
		$this->name = $name;

		return $this;
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

	public function getRoles(): array
	{
		return [
			'ROLE_USER',
			'ROLE_API',
		];
	}

	public function eraseCredentials(): void
	{
	}

	public function getUserIdentifier(): string
	{
		if ($this->id === null) {
			throw new \Exception('TODO'); // TODO: can not be in this state
		}

		return $this->id;
	}
}