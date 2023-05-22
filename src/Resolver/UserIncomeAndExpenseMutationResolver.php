<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use GraphQL\Type\Definition\FieldDefinition;

class UserIncomeAndExpenseMutationResolver implements MutationResolverInterface
{
	private const USER_INCOME_CLASS_NAME = 'UserIncome';

	public function __construct(
		private readonly DocumentManager $dm,
	) {}

	public function __invoke(?object $item, array $context): ?object
	{
		$classNamespace = get_class($item);
		$classNamespaceExploded = explode('\\', $classNamespace);
		$className = end($classNamespaceExploded);

		$multiplier = $className === self::USER_INCOME_CLASS_NAME ? 1 : -1;

		/** @var FieldDefinition $contextFieldDefinition */
		$contextFieldDefinition = $context['info']->fieldDefinition;
		switch ($contextFieldDefinition->getName()) {
			case 'create' . $className:
				$account = $item->getUserAccount();
				$accountBalance = $account->getBalance();
				$account->setBalance($accountBalance + ($item->getAmount() * $multiplier));

				$this->dm->persist($account);
				break;

			case 'update' . $className:
				$originalDocument = $this->dm->getRepository($classNamespace)->find($item->getId());
				$previousAmount = $originalDocument->getAmount();
				$difference = $item->getAmount() - $previousAmount;

				$account = $item->getUserAccount();
				$accountBalance = $account->getBalance();
				$account->setBalance($accountBalance + ($difference * $multiplier));

				$this->dm->persist($account);
				break;

			case 'delete' . $className:
				$account = $item->getUserAccount();
				$accountBalance = $account->getBalance();
				$account->setBalance($accountBalance + ($item->getAmount() * -$multiplier));

				$this->dm->persist($account);
				break;
		}

		return $item;
	}
}