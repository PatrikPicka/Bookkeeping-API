<?php

namespace App\Repository;

use App\Document\ApiToken;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\UnitOfWork;

class UserRepository extends DocumentRepository
{
	public function findByApiToken(string $apiToken): ?User
	{
		$apiToken = $this->dm->getRepository(ApiToken::class)->findOneBy(['token' => $apiToken]);

		if ($apiToken !== null) {
			return $apiToken->getUser();
		}

		return null;
	}
}