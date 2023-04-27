<?php

declare(strict_types = 1);

namespace App\Document\Trait;

trait CUDTrait
{
	use CreatedAtTrait;
	use UpdatedAtTrait;
	use DeletedAtTrait;
}