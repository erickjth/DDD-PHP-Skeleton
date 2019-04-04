<?php

declare(strict_types=1);

namespace App\Application\Command;

class CreateIdentityCommand
{
	/**
	 * @var string
	 */
	protected $firstName;

	/**
	 * @var string
	 */
	protected $lastName;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * Command constructor
	 *
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $email
	 * @param string $username
	 * @param string $password
	 */
	public function __construct(
		string $firstName,
		string $lastName,
		string $email,
		string $username,
		string $password
	)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Get the value of firstName
	 *
	 * @return  string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Get the value of lastName
	 *
	 * @return  string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Get the value of email
	 *
	 * @return  string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the value of username
	 *
	 * @return  string|null
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Get the value of password
	 *
	 * @return  string
	 */
	public function getPassword()
	{
		return $this->password;
	}
}
