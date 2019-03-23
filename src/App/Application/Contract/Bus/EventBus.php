<?php

declare(strict_types=1);

namespace App\Application\Contract\Bus;

/**
 * Interface Event Bus Contract
 */
interface EventBus
{
/**
     * @param object|Envelope $query
     *
     * @return mixed The handler returned value
     */
    public function dispatch($message);
}
