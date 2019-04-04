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
	private $username;
	private $email;
	private $emailVerified = 0;
	private $password;
	private $createdAt;
	private $updatedAt;
	private $deletedAt;

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
		$this->updatedAt = $createdAt;
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

	public function setLogin(string $username, string $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	public function setEmailVerified(bool $verified)
	{
		$this->emailVerified = $verified;
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

	/**
	 * Get the value of username
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Get the value of emailVerified
	 */
	public function getEmailVerified()
	{
		return $this->emailVerified;
	}

	/**
	 * Get the value of deletedAt
	 */
	public function getDeletedAt()
	{
		return $this->deletedAt;
	}

	/**
	 * Get the value of updatedAt
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
}

