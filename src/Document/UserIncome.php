<?php

namespace App\Document;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Document\Superclass\IncomeAndExpenseSuperclass;
use App\Resolver\UserIncomeAndExpenseMutationResolver;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.userIncomeGroup.user.id == user.id)"),
		new QueryCollection(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new DeleteMutation(
			resolver: UserIncomeAndExpenseMutationResolver::class,
			security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)",
			name: 'delete',
		),
		new Mutation(
			resolver: UserIncomeAndExpenseMutationResolver::class,
			security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)",
			name: 'create',
		),
		new Mutation(
			resolver: UserIncomeAndExpenseMutationResolver::class,
			security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)",
			name: 'update'
		),
	],
)]
#[ODM\Document]
class UserIncome extends IncomeAndExpenseSuperclass
{
	#[ApiProperty(writable: true)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: UserIncomeAndExpenseGroup::class)]
	protected UserIncomeAndExpenseGroup $userIncomeGroup;

	#[ApiProperty(writable: true)]
	#[ODM\ReferenceOne(nullable: false, storeAs: "id", targetDocument: UserAccount::class)]
	protected UserAccount $userAccount;

	public function getUserIncomeGroup(): UserIncomeAndExpenseGroup
	{
		return $this->userIncomeGroup;
	}

	public function setUserIncomeGroup(UserIncomeAndExpenseGroup $userIncomeGroup): void
	{
		$this->userIncomeGroup = $userIncomeGroup;
	}

	public function getUserAccount(): UserAccount
	{
		return $this->userAccount;
	}

	public function setUserAccount(UserAccount $userAccount): void
	{
		$this->userAccount = $userAccount;
	}
}