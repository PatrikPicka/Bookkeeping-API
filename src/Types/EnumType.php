<?php

namespace App\Types;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\HashType;
final class EnumType extends HashType
{
	use ClosureToPHP;

	public function convertToPHPValue($value)
	{
		$value = parent::convertToPHPValue($value);

		return $value['enumClass']::tryFrom($value['value']);
	}

	public function convertToDatabaseValue($value)
	{
		return parent::convertToDatabaseValue([
			'enumClass' => get_class($value),
			'value' => $value->value,
		]);
	}
}
