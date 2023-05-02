<?php

namespace App\Document;

use App\Document\Trait\IdTrait;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class ApiToken
{
	use IdTrait;

	#[ODM\Field(type: 'string', nullable: false)]
	private string $token;

	#[ODM\Field(type: 'date_immutable', nullable: true)]
	private DateTimeImmutable $expiresAt;

	#[ODM\EmbedOne(nullable: false, targetDocument: User::class)]
	private User $user;

	public function getToken(): string
	{
		return $this->token;
	}

	public function setToken(string $token): void
	{
		$this->token = $token;
	}

	public function getExpiresAt(): DateTimeImmutable
	{
		return $this->expiresAt;
	}

	public function setExpiresAt(DateTimeImmutable $expiresAt): void
	{
		$this->expiresAt = $expiresAt;
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