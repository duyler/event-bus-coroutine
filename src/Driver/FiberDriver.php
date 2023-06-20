<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Driver;

use Duyler\EventBusCoroutine\CoroutineDriverInterface;

class FiberDriver implements CoroutineDriverInterface
{
    public function process(callable $callback, mixed $value): mixed
    {
        return fn() => $callback($value);
    }
}
