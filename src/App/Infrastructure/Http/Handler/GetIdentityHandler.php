<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Application\Command\GetIdentityByUuidCommand;
use App\Application\Contract\MessageBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetIdentityHandler implements RequestHandlerInterface
{
	public function __construct(CommandBus $bus)
	{
		$this->bus = $bus;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$command = new GetIdentityByUuidCommand('7583317b-0d8f-4063-9130-4be513e323b6');

		$result = $this->bus->handle($command);

		return new JsonResponse([
			'data' => $result->present(),
		]);
	}
}
