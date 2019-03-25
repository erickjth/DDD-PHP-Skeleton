<?php

declare(strict_types=1);

namespace App\Application\Contract\MessageBus;

/**
 * Interface Command Bus Contract
 */
interface CommandBus
{
/**
     * @param object|Envelope $query
     *
     * @return mixed The handler returned value
     */
    public function handle($message);
}
