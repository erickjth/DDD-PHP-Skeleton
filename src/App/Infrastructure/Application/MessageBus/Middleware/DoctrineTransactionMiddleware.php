<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\MessageBus\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class DoctrineTransactionMiddleware implements MiddlewareInterface
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{;
		$this->entityManager= $entityManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		$this->entityManager->getConnection()->beginTransaction();

		try
		{
			$envelope = $stack->next()->handle($envelope, $stack);

			$this->entityManager->flush();

			$this->entityManager->getConnection()->commit();

			return $envelope;
		}
		catch (\Throwable $exception)
		{
			$this->entityManager->getConnection()->rollBack();
			throw $exception;
		}
	}
}
