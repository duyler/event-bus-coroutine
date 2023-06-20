<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Driver;

use Duyler\EventBusCoroutine\CoroutineDriverInterface;
use Duyler\EventBusCoroutine\Exception\CoroutineDriverNotAvailableException;
use Fiber;
use parallel\Runtime;

class ParallelDriver implements CoroutineDriverInterface
{
    public function process(callable $callback, mixed $value): mixed
    {
        if (extension_loaded('parallel') === false) {
            throw new CoroutineDriverNotAvailableException('parallel');
        }

        $runtime = new Runtime();
        $future = $runtime->run($callback, [$value]);

        return function () use ($future, $runtime) {
            while ($future->done() === false) {
                Fiber::suspend();
            }

            $future->cancel();
            $runtime->kill();

            return $future->value();
        };
    }
}
