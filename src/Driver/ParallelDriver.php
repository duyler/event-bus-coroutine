<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Driver;

use Duyler\EventBusCoroutine\CoroutineDriverInterface;
use Duyler\EventBusCoroutine\Exception\CoroutineDriverNotAvailableException;
use parallel\Runtime;

class ParallelDriver implements CoroutineDriverInterface
{
    public function process(callable $coroutine, mixed $value): mixed
    {
        if (extension_loaded('parallel') === false) {
            throw new CoroutineDriverNotAvailableException('parallel');
        }

        $runtime = new Runtime();
        return $runtime->run($coroutine, [$value]);
    }
}
