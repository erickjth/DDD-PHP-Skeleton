<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\ClientEntity;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
	{
		$clientRow = $this->database->createQueryBuilder()->
			select('id', 'name', 'secret', 'redirect_uri')->
			from('oauth_client')->
			where('identifier = ?')->
			setParameter(0, $clientIdentifier)->
			execute()->fetch();

		if (!$clientRow)
		{
			return;
		}

		if (
			$mustValidateSecret === true && (
				empty($clientRow['secret']) ||
				password_verify(
					(string) $clientSecret,
					$clientRow['secret']
				) === false
			)
		)
		{
			$this->logger->info('Failed authentication for client {client}', [
				'client' => $clientIdentifier,
			]);

			return;
		}

		return new ClientEntity($clientIdentifier, $clientRow['name'], $clientRow['redirect_uri']);
	}
}
