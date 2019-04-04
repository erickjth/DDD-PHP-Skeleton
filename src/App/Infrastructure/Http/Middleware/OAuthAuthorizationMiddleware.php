<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Diactoros\Response\RedirectResponse;

class OAuthAuthorizationMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		$user = $request->getAttribute(UserInterface::class);

		$authRequest = $request->getAttribute(AuthorizationRequest::class);

		// The user is authenticated:
		if ($user) {
			$authRequest->setUser($user);

			// This assumes all clients are trusted, but you could
			// handle consent here, or within the next middleware
			// as needed.
			$authRequest->setAuthorizationApproved(true);

			return $handler->handle($request);
		}

		// The user is not authenticated, show login form ...

		return new RedirectResponse('/oauth2/login');
	}
}
