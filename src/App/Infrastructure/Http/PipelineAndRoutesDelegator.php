<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\Handler;
use App\Infrastructure\Http\Middleware\DomainErrorMiddleware;
use App\Infrastructure\Http\Middleware\OAuthAuthorizationMiddleware;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Authentication\OAuth2;
use Zend\Expressive\Authentication\AuthenticationMiddleware;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware;
use Zend\Expressive\Router\Middleware\RouteMiddleware;
use Zend\ProblemDetails\ProblemDetailsNotFoundHandler;
use Zend\ProblemDetails\ProblemDetailsMiddleware;
use Zend\Stratigility\Middleware\ErrorHandler;

class PipelineAndRoutesDelegator
{
	public function __invoke(ContainerInterface $container, string $serviceName, callable $callback) : Application
	{
		/** @var $app Application */
		$app = $callback();

		// Setup pipeline:

		// The error handler should be the first (most outer) middleware to catch
		// all Exceptions.
		$app->pipe(ErrorHandler::class);
		$app->pipe(ServerUrlMiddleware::class);

		// Pipe more middleware here that you want to execute on every request:
		// - bootstrapping
		// - pre-conditions
		// - modifications to outgoing responses
		//
		// Piped Middleware may be either callables or service names. Middleware may
		// also be passed as an array; each item in the array must resolve to
		// middleware eventually (i.e., callable or service name).
		//
		// Middleware can be attached to specific paths, allowing you to mix and match
		// applications under a common domain.  The handlers in each middleware
		// attached this way will see a URI with the matched path segment removed.
		//
		$app->pipe('/api', [
			ProblemDetailsMiddleware::class,
			DomainErrorMiddleware::class,
		]);
		// i.e., path of "/api/member/profile" only passes "/member/profile" to $apiMiddleware
		// - $app->pipe('/api', $apiMiddleware);
		// - $app->pipe('/docs', $apiDocMiddleware);
		// - $app->pipe('/files', $filesMiddleware);

		// Register the routing middleware in the middleware pipeline.
		// This middleware registers the Zend\Expressive\Router\RouteResult request attribute.
		$app->pipe(RouteMiddleware::class);

		// The following handle routing failures for common conditions:
		// - HEAD request but no routes answer that method
		// - OPTIONS request but no routes answer that method
		// - method not allowed
		// Order here matters; the MethodNotAllowedMiddleware should be placed
		// after the Implicit*Middleware.
		$app->pipe(ImplicitHeadMiddleware::class);
		$app->pipe(ImplicitOptionsMiddleware::class);
		$app->pipe(MethodNotAllowedMiddleware::class);

		// Seed the UrlHelper with the routing results:
		$app->pipe(UrlHelperMiddleware::class);

		// Add more middleware here that needs to introspect the routing results; this
		// might include:
		//
		// - route-based authentication
		// - route-based validation
		// - etc.

		// Register the dispatch middleware in the middleware pipeline
		$app->pipe(DispatchMiddleware::class);

		$app->pipe(ProblemDetailsNotFoundHandler::class);

		// At this point, if no Response is returned by any middleware, the
		// NotFoundHandler kicks in; alternately, you can provide other fallback
		// middleware to execute.
		$app->pipe(NotFoundHandler::class);

		// Setup routes:

		/**
		 * Setup routes with a single request method:
		 *
		 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
		 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
		 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
		 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
		 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
		 *
		 * Or with multiple request methods:
		 *
		 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
		 *
		 * Or handling all request methods:
		 *
		 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
		 *
		 * or:
		 *
		 * $app->route(
		 *     '/contact',
		 *     App\Handler\ContactHandler::class,
		 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
		 *     'contact'
		 * );
		 */

		// Oauth 2
		$app->post('/oauth2/token', OAuth2\TokenEndpointHandler::class);

		$app->route('/oauth2/authorize', [
			OAuth2\AuthorizationMiddleware::class,
			OAuthAuthorizationMiddleware::class,
			OAuth2\AuthorizationHandler::class
		], ['GET', 'POST']);

		$app->route('/api/ping', Handler\PingHandler::class, ['GET'], 'api.ping');

		$app->get('/api/identity', [
			AuthenticationMiddleware::class,
			Handler\GetIdentityHandler::class
		], 'identity.get');

		$app->post('/api/identity', Handler\CreateIdentityHandler::class, 'identity.create');

		return $app;
	}
}
