<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\IdentityId;
use DateTimeImmutable;

class Identity
{
	private $id;
	private $identityId;
	private $firstName;
	private $lastName;
	private $email;
	private $createdAt;

	public function __construct(
		IdentityId $identityId,
		string $firstName,
		string $lastName,
		string $email,
		DateTimeImmutable $createdAt
	)
	{
		$this->identityId = $identityId;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->createdAt = $createdAt;
	}

	public static function create(
		IdentityId $identityId,
		string $firstName,
		string $lastName,
		string $email
	)
	{
		return new self(
			$identityId,
			$firstName,
			$lastName,
			$email,
			new DateTimeImmutable('now')
		);
	}

	/**
	 * Get the value of identityId
	 */
	public function getIdentityId() : IdentityId
	{
		return $this->identityId;
	}

	/**
	 * Get the value of firstName
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Get the value of lastName
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Get the value of email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the value of createdAt
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
}

