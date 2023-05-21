<?php

namespace App\Document\Superclass;

use ApiPlatform\Metadata\ApiProperty;
use App\Document\DocumentInterface;
use App\Document\Trait\CUDTrait;
use App\Document\Trait\IdTrait;
use App\Document\Trait\NameTrait;
use App\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
#[ODM\InheritanceType('COLLECTION_PER_CLASS')]
abstract class IncomeAndExpenseGroupSuperclass implements DocumentInterface
{
	use IdTrait;
	use NameTrait;
	use CUDTrait;

	#[ApiProperty(writable: true)]
	#[ODM\Field(type: 'string', nullable: false, options: ['default' => '#ffffff'])]
	protected string $color;

	#[ApiProperty(writable: true)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: User::class)]
	protected User $user;

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function setColor(string $color): void
	{
		$this->color = $color;
	}
}