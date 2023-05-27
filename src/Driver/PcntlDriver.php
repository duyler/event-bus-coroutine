<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Driver;

use Duyler\EventBusCoroutine\CoroutineDriverInterface;

class PcntlDriver implements CoroutineDriverInterface
{
    public function process(callable $coroutine, mixed $value): void
    {
        if (extension_loaded('pcntl')) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                $coroutine($value);
            }
        }
    }
}
