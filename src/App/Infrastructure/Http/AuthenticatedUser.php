<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Domain\Entity\Identity;
use Zend\Expressive\Authentication\UserInterface;

final class AuthenticatedUser implements UserInterface
{
	/**
     * @var Identity
     */
    private $identity;

    /**
     * @var string[]
     */
    private $roles;

    /**
     * @var array
     */
    private $details;

    public function __construct(Identity $identity, array $roles = [], array $details = [])
    {
        $this->identity = $identity;
        $this->roles = $roles;
        $this->details = $details;
	}

    public function getIdentity() : string
    {
        return (string) $this->identity->identityId;
    }

    public function getRoles() : array
    {
        return $this->roles;
    }

    public function getDetails() : array
    {
        return $this->details;
    }

    /**
     * @param mixed $default Default value to return if no detail matching
     *     $name is discovered.
     * @return mixed
     */
    public function getDetail(string $name, $default = null)
    {
        return $this->details[$name] ?? $default;
    }
}
