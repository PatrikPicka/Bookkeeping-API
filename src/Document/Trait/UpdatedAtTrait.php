<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait UpdatedAtTrait
{
	#[ApiProperty]
	#[ODM\Field(type: 'date_immutable', nullable: false)]
	#[Assert\NotBlank]
	protected DateTimeImmutable $updatedAt;

	public function getUpdatedAt(): DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTimeImmutable $updatedAt): DocumentInterface
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}
}