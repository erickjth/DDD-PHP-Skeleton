<?php

declare(strict_types=1);

namespace App\Application\Contract\MessageBus;

/**
 * Interface QueryBus Contract
 */
interface QueryBus
{
    /**
     * @param object|Envelope $query
     *
     * @return mixed The handler returned value
     */
    public function query($message);
}
