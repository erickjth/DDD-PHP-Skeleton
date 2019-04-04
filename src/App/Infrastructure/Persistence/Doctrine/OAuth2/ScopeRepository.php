<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\ScopeEntity;

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getScopeEntityByIdentifier($identifier)
	{
		$scope = $this->database->createQueryBuilder()->
			select('id')->
			from('oauth_scopes')->
			where('identifier = ?')->
			setParameter(0, $identifier)->
			execute()->fetchColumn();

		if ($scope === null)
		{
			return;
		}

		$scope = new ScopeEntity();
		$scope->setIdentifier($identifier);

		return $scope;
	}

	/**
	 * {@inheritdoc}
	 */
	public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
	{
		return $scopes;
	}
}
