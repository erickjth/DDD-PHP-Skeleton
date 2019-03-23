<?php

declare(strict_types=1);

namespace App\Infrastructure\Container\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
	/**
	 * Invokable method
	 *
	 * @param  ContainerInterface $container Service container
	 *
	 * @return LoggerInterface  Logger instance
	 */
	public function __invoke(ContainerInterface $container) : LoggerInterface
	{
		$handlers = [
			new StreamHandler(fopen('php://stdout', 'w'), Logger::DEBUG),
			new StreamHandler(fopen('php://stderr', 'w'), Logger::WARNING),
		];

		$processors = [
			new PsrLogMessageProcessor,
		];

		$logger = new Logger('app_logger', $handlers, $processors);

		return $logger;
	}
}
