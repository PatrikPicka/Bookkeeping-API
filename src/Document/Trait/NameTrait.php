<?php

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use App\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameTrait
{
	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: false)]
	#[Assert\NotBlank]
	protected string $name;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): DocumentInterface
	{
		$this->name = $name;

		return $this;
	}
}