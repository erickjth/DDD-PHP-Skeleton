<?php

declare(strict_types=1);

namespace App\Application\Command;

class PingCommand
{
	/**
	 * Epoch time
	 *
	 * @var int
	 */
	protected $time;

	/**
	 * Command constructor
	 *
	 * @param int $time
	 */
	public function __construct(int $time)
	{
		$this->time = $time;
	}

	/**
	 * Get epoch time
	 *
	 * @return  int
	 */
	public function getTime() : int
	{
		return $this->time;
	}
}
