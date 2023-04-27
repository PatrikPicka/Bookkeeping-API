<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use App\Document\DocumentInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait ActiveTrait
{
	#[ODM\Field(type: 'bool', nullable: false)]
	#[Assert\NotBlank]
	protected bool $active = false;

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): DocumentInterface
	{
		$this->active = $active;

		return $this;
	}
}