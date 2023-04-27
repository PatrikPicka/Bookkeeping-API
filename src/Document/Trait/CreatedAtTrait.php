<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait CreatedAtTrait
{
	#[ApiProperty(writable: false)]
	#[ODM\Field(type: 'date_immutable', nullable: false)]
	#[Assert\NotBlank]
	protected DateTimeImmutable $createdAt;

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): DocumentInterface
	{
		$this->createdAt = $createdAt;

		return $this;
	}
}