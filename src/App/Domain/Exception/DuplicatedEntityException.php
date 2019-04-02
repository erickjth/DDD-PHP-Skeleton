<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class DuplicatedEntityException extends DomainException
{
	/**
	 * @var string
	 */
	private $class;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * Exception constructor
	 *
	 * @param string $class
	 * @param string $id
	 */
	public function __construct(string $class, string $id)
	{
		$this->class = $class;
		$this->id = $id;

		parent::__construct(
			sprintf('Duplicated entity (%s) with id "%s".', $class, $id),
			DomainException::ENTITY_NOT_FOUND
		);
	}
}
