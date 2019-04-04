<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Psr\Log\LoggerInterface;

abstract class AbstractRepository
{
	/**
	 * @var  \Doctrine\DBAL\Driver\Connection
	 */
	protected $database;

	/**
	 * @var \Psr\Log\LoggerInterface Logger
	 */
	protected $logger;

	/**
	 * Constructs
	 *
	 * @param \Doctrine\DBAL\Driver\Connection   $database   Database Handle
	 */
	public function __construct(DriverConnection $database, LoggerInterface $logger)
	{
		$this->database = $database;
		$this->logger = $logger;
	}
}
