<?php

declare(strict_types=1);

namespace App\Domain\Identifier;

use App\Domain\Contract\Identifier;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidIdentifier implements Identifier
{
	protected $uuid;

	function __construct(UuidInterface $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * Generate a new Identifier
	 *
	 * @return Identifier
	 */
	public static function generate()
	{
		return new static(Uuid::uuid4());
	}

	/**
	 * Creates an identifier object from a string
	 *
	 * @param $string
	 * @return Identifier
	 */
	public static function fromString($string)
	{
		return new static(Uuid::fromString($string));
	}

	/**
	 * Determine equality with another Value Object
	 *
	 * @param  Identifier $other
	 * @return bool
	 */
	public function equals(Identifier $other) : bool
	{
		return $this->toString() === $other->toString();
	}

	/**
	 * Return the identifier as a string
	 *
	 * @return string
	 */
	public function toString() : string
	{
		return $this->uuid->toString();
	}

	/**
	 * Return the identifier as a string
	 *
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->uuid->toString();
	}
}
