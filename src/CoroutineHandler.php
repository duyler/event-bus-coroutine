<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

readonly class CoroutineHandler
{
    public function __construct(
        private CoroutineDriverProvider $driverProvider,
    ) {
    }

    public function handle(callable $handler): void
    {

    }
}
