<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory;

use App\Infrastructure\Application\Bus\QueryBus;
use App\Infrastructure\Http\Handler\PingHandler;
use Psr\Container\ContainerInterface;

class PingHandlerFactory
{
    public function __invoke(ContainerInterface $container) : PingHandler
    {
        return new PingHandler($container->get(QueryBus::class));
    }
}
