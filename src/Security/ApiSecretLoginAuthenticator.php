<?php

namespace App\Security;

use App\Constant\RoleConstant;
use App\Document\Role;
use App\Document\User;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Google_Client;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidPayloadException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiSecretLoginAuthenticator extends AbstractAuthenticator
{
	const API_AUTH_HEADER_NAME = 'x-api-secret';

	public function __construct(
		private readonly DocumentManager $dm,
		private readonly string $apiToken,
		private readonly AuthenticationSuccessHandler $successHandler
	) {
	}

	public function supports(Request $request): ?bool
	{
		return str_starts_with($request->getPathInfo(), '/api/login')   ;
	}

	public function authenticate(Request $request): Passport
	{
		$apiToken = $request->headers->get(key: self::API_AUTH_HEADER_NAME);

		if (empty($apiToken)) {
			throw new CustomUserMessageAuthenticationException(message: 'No API secret provided');
		} elseif ($apiToken !== $this->apiToken) {
			throw new CustomUserMessageAuthenticationException(message: 'Wrong API secret provided');
		}

		if ($request->getContentTypeFormat() !== 'json') {
			throw new CustomUserMessageAuthenticationException(message: 'Wrong content type. JSON content type supported only.');
		}

		$decodedBody = json_decode($request->getContent());

		if (empty($decodedBody->token)) {
			throw new InvalidPayloadException('You have to send google oauth token to login.');
		}

		try {
			$client = new Google_Client();
			$data = $client->verifyIdToken($decodedBody->token);
		} catch (Exception $exception) {
			throw new InvalidPayloadException('JWT is not valid.');
		}

		if ($data === false) {
			throw new InvalidPayloadException('JWT is not valid.');
		}

		$user = $this->getOrCreateUser($data['email'], $data['name']);

		return new SelfValidatingPassport(
			new UserBadge($user->getId(), function (string $userId): User {
				$user = $this->dm->getRepository(User::class)->find($userId);

				if ($user === null) {
					throw new UserNotFoundException(message: 'There was an error while creating new user account.');
				}

				return $user;
			}),
		);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		return $this->successHandler->onAuthenticationSuccess($request, $token);
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		$data = [
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
		];

		return new JsonResponse($data, $exception->getCode());
	}

	public function getOrCreateUser(string $email, string $name): UserInterface
	{
		$user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);

		if ($user === null) {
			$user = new User();
			$user
				->setEmail($email)
				->setName($name)
				->addUserRole(
					$this->dm
						->getRepository(Role::class)
						->findOneBy(['name' => RoleConstant::ROLE_USER])
				)
				->setCreatedAt(new DateTimeImmutable())
				->setUpdateddAt(new DateTimeImmutable());

			$this->dm->persist($user);
			$this->dm->flush();
		}

		return $user;
	}
}
