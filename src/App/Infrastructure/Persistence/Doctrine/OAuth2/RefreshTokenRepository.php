<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\RefreshTokenEntity;

class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntityInterface)
	{
		$accessToken = $refreshTokenEntityInterface->getAccessToken();

		$accessTokenId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_access_token')->
			where('identifier = ?')->
			setParameter(0, $accessToken->getIdentifier())->
			execute()->fetchColumn();

		$this->database->insert('oauth_refresh_token', [
			'identifier' => $refreshTokenEntityInterface->getIdentifier(),
			'revoked' => 0,
			'access_token_id' => $accessTokenId,
			'created_dt' => gmdate('Y-m-d h:i:s'),
			'expiration_dt' => $refreshTokenEntityInterface->getExpiryDateTime()->format('Y-m-d h:i:s'),
		]);

		$this->logger->info('New refresh_token {refresh_token} for access_token {access_token}.', [
			'refresh_token' => $refreshTokenEntityInterface->getIdentifier(),
			'access_token' => $accessToken->getIdentifier(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function revokeRefreshToken($tokenId)
	{
		$tokenRealId = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_refresh_token')->
			where('identifier = ?')->
			setParameter(0, $tokenId)->
			execute()->fetchColumn();

		if ($tokenRealId !== null)
		{
			$this->database->update('oauth_refresh_token', [
				'revoked' => 1,
			], ['id' => $tokenRealId]);

			$this->logger->info('Revoke refresh token {tokenId}', ['tokenId' => $tokenId]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function isRefreshTokenRevoked($tokenId)
	{
		$revoked = $this->database->createQueryBuilder()->
			select('revoked')->
			from('oauth_refresh_token')->
			where('identifier = ?')->
			setParameter(0, $tokenId)->
			execute()->fetchColumn();

		return (boolean) $revoked;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNewRefreshToken()
	{
		return new RefreshTokenEntity();
	}
}
