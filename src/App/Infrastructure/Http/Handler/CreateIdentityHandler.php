<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Application\Command\CreateIdentityCommand;
use App\Application\Contract\MessageBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateIdentityHandler implements RequestHandlerInterface
{
	public function __construct(CommandBus $bus)
	{
		$this->bus = $bus;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$command = new CreateIdentityCommand(
			'John',
			'Doe',
			'john@doe.org',
			'john@doe.org',
			password_hash('abc1234', PASSWORD_DEFAULT)
		);

		$result = $this->bus->handle($command);

		return new JsonResponse([
			'data' => $result->present(),
		]);
	}
}
