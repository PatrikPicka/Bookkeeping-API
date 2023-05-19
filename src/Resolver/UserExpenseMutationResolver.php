<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;

class UserExpenseMutationResolver implements MutationResolverInterface
{
	public function __invoke(?object $item, array $context): ?object
	{
		dd(['object' => $item, 'context' => $context]);
	}
}