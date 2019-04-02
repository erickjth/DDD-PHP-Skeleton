<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class EntityNotFoundException extends DomainException
{
	/**
	 * @var string
	 */
	private $class;

	/**
	 * Exception constructor
	 *
	 * @param string $class
	 */
	public function __construct(string $class)
	{
		$this->class = $class;

		parent::__construct(
			sprintf('Entity (%s) cannot be found.', $class),
			DomainException::ENTITY_NOT_FOUND
		);
	}
}
