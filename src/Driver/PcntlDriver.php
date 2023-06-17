<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Driver;

use Duyler\EventBusCoroutine\CoroutineDriverInterface;
use Duyler\EventBusCoroutine\Exception\CoroutineDriverNotAvailableException;

class PcntlDriver implements CoroutineDriverInterface
{
    public function process(callable $coroutine, mixed $value): mixed
    {
        if (extension_loaded('pcntl') === false) {
            throw new CoroutineDriverNotAvailableException('pcntl');
        }

        $pid = pcntl_fork();
        if ($pid === 0) {
            $coroutine($value);
            exit();
        }

        return null;
    }
}
