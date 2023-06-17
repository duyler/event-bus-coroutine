<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Dto;

use Closure;

readonly class Coroutine
{
    public function __construct(
        public string           $actionId,
        public string           $driver,
        public string | Closure $callback = '',
        public string | Closure $handler = '',
        public array            $classMap = [],
        public array            $providers = [],
    ) {
    }
}
