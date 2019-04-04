<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Application\Command\PingCommand;
use App\Application\Contract\MessageBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;

class PingHandler implements RequestHandlerInterface
{
	public function __construct(CommandBus $bus)
	{
		$this->bus = $bus;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$args = [time()];

		$command = new PingCommand(...$args);

		$result = $this->bus->handle($command);

		return new JsonResponse($result);
	}
}
