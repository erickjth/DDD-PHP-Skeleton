<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AuthCodeEntity;

class AuthCodeRepository extends AbstractRepository implements AuthCodeRepositoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
	{
		$clientId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_client')->
			where('identifier = ?')->
			setParameter(0, $authCodeEntity->getClient()->getIdentifier())->
			execute()->fetchColumn();

		$this->database->insert('oauth_authorization_code', [
			'identifier' => $authCodeEntity->getIdentifier(),
			'revoked' =>0,
			'client_id' => $clientId,
			'scope' => json_encode($authCodeEntity->getScopes()),
			'redirect_uri' => $authCodeEntity->getRedirectUri(),
			'created_dt' => gmdate('Y-m-d h:i:s'),
			'expiration_dt' => $authCodeEntity->getExpiryDateTime()->format('Y-m-d h:i:s'),
		]);

		$this->logger->info('New auth code {token} for client {client}.', [
			'token' => $authCodeEntity->getIdentifier(),
			'client' => $clientId,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function revokeAuthCode($codeId)
	{
		$codeRealId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_authorization_code')->
			where('identifier = ?')->
			setParameter(0, $codeId)->
			execute()->fetchColumn();

		if ($codeRealId !== null)
		{
			$this->database->update('oauth_authorization_code', [
				'revoked' => 1,
			], ['id' => $codeRealId]);

			$this->logger->info('Revoke oauth token {codeId}', ['codeId' => $codeId]);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function isAuthCodeRevoked($codeId)
	{
		$revoked = $this->database->createQueryBuilder()->
			select('revoked')->
			from('oauth_authorization_code')->
			where('identifier = ?')->
			setParameter(0, $codeId)->
			execute()->fetchColumn();

		return (boolean) $revoked;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNewAuthCode()
	{
		return new AuthCodeEntity;
	}

}
