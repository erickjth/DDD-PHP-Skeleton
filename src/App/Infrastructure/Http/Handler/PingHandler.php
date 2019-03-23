<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler;

use App\Application\Command\PingCommand;
use App\Application\Contract\Bus\QueryBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;

class PingHandler implements RequestHandlerInterface
{
	public function __construct(QueryBus $queryBus)
	{
		$this->queryBus = $queryBus;
	}

	public function handle(ServerRequestInterface $request) : ResponseInterface
	{
		$command = new PingCommand(time());

		$result = $this->queryBus->query($command);

		return new JsonResponse($result);
	}
}