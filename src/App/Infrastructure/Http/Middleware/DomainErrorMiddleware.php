<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use App\Domain\Exception\EntityNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

class DomainErrorMiddleware implements MiddlewareInterface
{
	private $problemDetailsFactory;

	public function __construct(ProblemDetailsResponseFactory $problemDetailsFactory)
	{
		$this->problemDetailsFactory = $problemDetailsFactory;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		try
		{
			$response = $handler->handle($request);

			return $response;
		}
		catch (EntityNotFoundException $e)
		{
			return $this->problemDetailsFactory->createResponse(
				$request,
				404,
				$e->getMessage(),
				'',
				'',
				['code' => $e->getCode()]
			);
		}
		catch (\Exception $e)
		{
			// @TODO: if debug, show details
			throw $e;
		}
	}
}
