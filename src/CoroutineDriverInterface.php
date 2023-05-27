<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

interface CoroutineDriverInterface
{
    public function process(callable $coroutine, mixed $value): void;
}
