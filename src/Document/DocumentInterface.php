<?php

declare(strict_types = 1);

namespace App\Document;

use DateTimeImmutable;

interface DocumentInterface
{
	public function getId(): string;

	public function getCreatedAt(): DateTimeImmutable;

	public function setCreatedAt(DateTimeImmutable $createdAt): DocumentInterface;

	public function getUpdatedAt(): DateTimeImmutable;

	public function setUpdatedAt(DateTimeImmutable $updatedAt): DocumentInterface;

	public function getDeletedAt(): ?DateTimeImmutable;

	public function setDeletedAt(?DateTimeImmutable $expiredAt): DocumentInterface;
}