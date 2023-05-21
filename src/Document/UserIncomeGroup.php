<?php

namespace App\Document;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Document\Superclass\IncomeAndExpenseGroupSuperclass;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ApiResource(
	operations: [
		new GetCollection(controller: NotFoundAction::class),
	],
	graphQlOperations: [
		new Query(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new QueryCollection(security: "(is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN')) or (is_granted('ROLE_USER') and object.user.id == user.id)"),
		new DeleteMutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'delete'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'create'),
		new Mutation(security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_USER_ADMIN') or (is_granted('ROLE_USER') and object.user.id == user.id)", name: 'update'),
	],
)]
#[ODM\Document]
class UserIncomeGroup extends IncomeAndExpenseGroupSuperclass
{
}