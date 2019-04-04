<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth2;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\UserEntity;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
	{
		$identity = $this->database->createQueryBuilder()->
			select('*')->
			from('identity')->
			where('email = ?')->
			setParameter(0, $username)->
			execute()->fetch();

		if ($identity !== [] && password_verify($password, $identity['password']) === true)
		{
			$user = new UserEntity($identity['uuid']);

			return $user;
		}

		return;
	}
}
