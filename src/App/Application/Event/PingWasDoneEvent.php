<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Application\Contract\Event\Event;

class PingWasDoneEvent implements Event
{
	private $time;

	public function __construct(int $time)
	{
		$this->time = $time;
	}

	public function getTime()
	{
		return $this->time;
	}
}
