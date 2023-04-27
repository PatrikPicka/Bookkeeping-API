<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use App\Document\DocumentInterface;
use App\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait UserTrait
{
	#[ODM\ReferenceOne(nullable: false, targetDocument: User::class)]
	#[Assert\NotBlank]
	protected User $user;

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): DocumentInterface
	{
		$this->user = $user;

		return $this;
	}
}