<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait IdTrait
{
	#[ApiProperty(writable: false)]
	#[ODM\Id]
	protected string $id;

	public function getId(): string
	{
		return $this->id;
	}
}