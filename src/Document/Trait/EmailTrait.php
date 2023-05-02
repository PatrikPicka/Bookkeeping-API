<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;


trait EmailTrait
{
	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: false)]
	#[Assert\NotBlank]
	#[Assert\Unique]
	protected string $email;

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): DocumentInterface
	{
		$this->email = $email;

		return $this;
	}
}