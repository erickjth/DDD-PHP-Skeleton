<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Command;
use App\Application\Handler;
use App\Infrastructure\Container;
use Psr\Log\LoggerInterface;

/**
 * The configuration provider for the App module
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'infrastructure' => $this->getSettings(),
            'messenger' => $this->getMessenger(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                LoggerInterface::class => Container\Factory\LoggerFactory::class,
            ],
            'extensions' => [
                'infrastructure' => Container\InfrastructureExtension::class
            ],
        ];
    }

    public function getSettings() : array
    {
        return [];
    }

    public function getMessenger() : array
    {
        return [
            'default_bus'        => 'messenger.bus.command',
            'default_middleware' => true,
            'buses'              => [
                'messenger.bus.query'   => [
                    'allows_no_handler' => false,
                    'handlers'          => [
                        Command\PingCommand::class => Handler\PingHandler::class
                    ],
                    'middleware'        => [],
                    'routes'            => [],
                ],
            ],
        ];
    }
}
