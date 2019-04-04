<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AccessTokenEntity;

class AccessTokenRepository extends AbstractRepository implements AccessTokenRepositoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
	{
		$accessToken = new AccessTokenEntity();

		$accessToken->setClient($clientEntity);

		foreach ($scopes as $scope)
		{
			$accessToken->addScope($scope);
		}

		$accessToken->setUserIdentifier($userIdentifier);

		return $accessToken;
	}

	/**
	 * {@inheritDoc}
	 */
	public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
	{
		$clientId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_client')->
			where('identifier = ?')->
			setParameter(0, $accessTokenEntity->getClient()->getIdentifier())->
			execute()->fetchColumn();

		$identityId = null;

		if ($accessTokenEntity->getUserIdentifier() !== null)
		{
			$identityId = $this->database->createQueryBuilder()->
				select('id')->
				from('identity')->
				where('uuid = ?')->
				setParameter(0, $accessTokenEntity->getUserIdentifier())->
				execute()->fetchColumn();
		}

		$this->database->insert('oauth_access_token', [
			'identifier' =>  $accessTokenEntity->getIdentifier(),
			'revoked' =>0,
			'scope' => json_encode($accessTokenEntity->getScopes()),
			'client_id' => $clientId,
			'identity_id' => $identityId,
			'created_dt' => gmdate('Y-m-d h:i:s'),
			'expiration_dt' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d h:i:s'),
		]);

		$this->logger->info('New persisted token {token} with client {client} and identity {identity}.', [
			'token' => $accessTokenEntity->getIdentifier(),
			'client' => $clientId,
			'identity' => $identityId,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function revokeAccessToken($tokenId)
	{
		$tokenRealId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_access_token')->
			where('identifier = ?')->
			setParameter(0, $tokenId)->
			execute()->fetchColumn();

		if ($tokenRealId !== null)
		{
			$this->database->update('oauth_access_token', [
				'revoked' => 1,
			], ['id' => $tokenRealId]);

			$this->logger->info('Revoke access token {tokenId}', ['tokenId' => $tokenId]);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function isAccessTokenRevoked($tokenId)
	{
		$revoked = $this->database->createQueryBuilder()->
			select('revoked')->
			from('oauth_access_token')->
			where('identifier = ?')->
			setParameter(0, $tokenId)->
			execute()->fetchColumn();

		return (boolean) $revoked;
	}
}
