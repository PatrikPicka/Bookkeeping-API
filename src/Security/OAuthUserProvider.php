<?php

namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthUserProvider implements UserProviderInterface
{
	public function __construct(
		protected DocumentManager $dm,
		private UserInterface $user,
	) {
	}

	public function supportsClass(string $class)
	{
		return User::class === $class;
	}

	public function loadUserByIdentifier(string $identifier): UserInterface
	{
		$this->user = $this->dm->find(User::class, $identifier);

		return $this->user;
	}

	public function refreshUser(UserInterface $user)
	{
		$this->user = $user;

		return $this->user;
	}
}