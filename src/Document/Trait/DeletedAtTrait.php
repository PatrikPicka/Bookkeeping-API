<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait DeletedAtTrait
{
	#[ApiProperty()]
	#[ODM\Field(type: 'date_immutable', nullable: true)]
	protected DateTimeImmutable|null $deletedAt = null;

	public function getDeletedAt(): ?DateTimeImmutable
	{
		return $this->deletedAt;
	}

	public function setDeletedAt(?DateTimeImmutable $deletedAt): DocumentInterface
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}
}