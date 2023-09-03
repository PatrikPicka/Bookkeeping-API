<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Constant\OAuthConstant;
use App\Document\Role;
use App\Document\User;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class OAuthController extends AbstractController
{
	protected const CONTINUE_SESSION_NAME = 'oauth_continue';

	public function __construct(
		private DocumentManager $dm,
		private TokenStorageInterface $tokenStorage,
		private EventDispatcherInterface $eventDispatcher,
	) {
	}

	#[Route('/oauth', name: 'google_auth_login')]
	public function indexAction(ClientRegistry $clientRegistry): RedirectResponse
	{
		return $clientRegistry
			->getClient('google_main') // key used in config/packages/knpu_oauth2_client.yaml
			->redirect(['email']);
	}

	#[Route('/oauth/check', name: 'google_auth_check')]
	public function connectCheckAction(Request $request, Session $session, ClientRegistry $clientRegistry): Response|RedirectResponse
	{
		/** @var GoogleClient $client */
		$resourceOwner = $clientRegistry->getClient('google_main');

		try {
			/** @var GoogleUser $user */
			$resourceUser = $resourceOwner->fetchUser();
		} catch (IdentityProviderException $e) {
			return $this->render('auth/error.html.twig', [
				'message' => $e->getMessage(),
			]);
		}

		$user = $this->getUserFromResourceUser($resourceUser, false);

		if ($user === null) {
			$session->remove(static::CONTINUE_SESSION_NAME);

			return $this->redirectToRoute('google_auth_login', [
				'error' => 'error_inactive_account',
			]);
		}

		$this->authorizeUser($user, $request, $session);

		$continue = $session->get(static::CONTINUE_SESSION_NAME);

		$session->remove(static::CONTINUE_SESSION_NAME);

		return $this->redirect($continue ?? '/api');
	}

	private function getUserFromResourceUser(ResourceOwnerInterface $resourceUser, bool $createIfNotFound = true): ?User
	{
		$userRepository = $this->dm->getRepository(User::class);

		$user = $userRepository->findOneBy([
			'email' => $resourceUser->getEmail(),
		]);

		if ($user === null && $createIfNotFound) {
			$role = $this->dm->getRepository(Role::class)->findOneBy(['name' => OAuthConstant::ROLE_USER]);

			if ($role === null) {
				$role = new Role();
				$role->setName(OAuthConstant::ROLE_USER)
					->setActive(true)
					->setCreatedAt(new DateTimeImmutable())
					->setUpdatedAt(new DateTimeImmutable());

				$this->dm->persist($role);
				$this->dm->flush();
			}

			$user = new User();
			$user->setActive(true)
				->setAuthId($resourceUser->getId())
				->setEmail($resourceUser->getEmail())
				->setName($resourceUser->getName())
				->addUserRole($role)
				->setCreatedAt(new DateTimeImmutable())
				->setUpdatedAt(new DateTimeImmutable());

			$this->dm->persist($user);
			$this->dm->flush();
		}

		return $user;
	}

	private function authorizeUser(User $user, Request $request, Session $session): void
	{
		$token = new PreAuthenticatedToken($user, 'main', $user->getRoles());

		$this->tokenStorage->setToken($token);
		$session->set('_security_primary_auth', serialize($token));
		$session->save();

		$event = new InteractiveLoginEvent($request, $token);
		$this->eventDispatcher->dispatch($event, SecurityEvents::INTERACTIVE_LOGIN);
	}
}