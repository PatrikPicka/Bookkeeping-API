<?php

namespace App\Document\Superclass;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\DateTrait;
use App\Document\Trait\IdTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document]
#[ODM\InheritanceType('COLLECTION_PER_CLASS')]
abstract class IncomeAndExpenseSuperclass
{
	use IdTrait;
	use CUDTrait;
	use DateTrait;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'float', nullable: false)]
	#[Assert\NotBlank]
	protected float $amount;

	public function getAmount(): float
	{
		return $this->amount;
	}

	public function setAmount(float $amount): void
	{
		$this->amount = $amount;
	}
}