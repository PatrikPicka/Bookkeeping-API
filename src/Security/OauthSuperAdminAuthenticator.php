<?php

namespace App\Security;

use App\Constant\RoleConstant;
use App\Constant\SecurityConstant;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OauthSuperAdminAuthenticator extends AbstractAuthenticator
{
	public function supports(Request $request): ?bool
	{
		return
			!$request->headers->has(SecurityConstant::API_AUTH_HEADER_NAME) &&
			!str_starts_with($request->getPathInfo(), '/oauth');
	}

	public function authenticate(Request $request): Passport
	{
		if (!$request->getSession()->has(SecurityConstant::OAUTH_SESSION_NAME)) {
			throw new AuthenticationException('You have to be logged in to get acces to this app.');
		}

		/** @var PreAuthenticatedToken $preAuthenticatedToken */
		$preAuthenticatedToken = unserialize($request->getSession()->get(SecurityConstant::OAUTH_SESSION_NAME));

		if (!in_array(RoleConstant::ROLE_SUPER_ADMIN, $preAuthenticatedToken->getRoleNames())) {
			throw new CustomUserMessageAuthenticationException('You don\'t have permissions for this page.');
		}

		return new SelfValidatingPassport(new UserBadge($preAuthenticatedToken->getUserIdentifier()));
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		return null;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		$data = [
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}
}