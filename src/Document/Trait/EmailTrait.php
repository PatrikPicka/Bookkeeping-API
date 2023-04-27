<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use App\Document\DocumentInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;


trait EmailTrait
{
	#[ODM\Field(type: 'date_immutable', nullable: false)]
	#[Assert\NotBlank]
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