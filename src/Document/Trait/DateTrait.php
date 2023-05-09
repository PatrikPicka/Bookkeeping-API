<?php

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait DateTrait
{
	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'date_immutable', nullable: false, options: ['default' => new DateTimeImmutable()])]
	protected DateTimeImmutable $date;

	public function getDate(): DateTimeImmutable
	{
		return $this->date;
	}

	public function setDate(DateTimeImmutable $date): void
	{
		$this->date = $date;
	}
}