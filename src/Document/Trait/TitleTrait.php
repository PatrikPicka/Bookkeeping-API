<?php

declare(strict_types = 1);

namespace App\Document\Trait;

use App\Document\DocumentInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

trait TitleTrait
{
	#[ODM\Field(type: 'date_immutable', nullable: false)]
	#[Assert\NotBlank]
	protected string $title;

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): DocumentInterface
	{
		$this->title = $title;

		return $this;
	}
}