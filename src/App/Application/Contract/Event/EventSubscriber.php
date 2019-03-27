<?php

declare(strict_types=1);

namespace App\Application\Contract\Event;

/**
 * Interface EventSubscriber Contract
 */
interface EventSubscriber
{
	public function getSubscribedEvents();
}
