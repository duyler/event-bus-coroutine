<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Dto;

use Closure;

final readonly class Coroutine
{
    public function __construct(
        public string           $actionId = '',
        public string           $driver = '',
        public string | Closure $promise = '',
        public string | Closure $callback = '',
        public array            $classMap = [],
        public array            $providers = [],
    ) {
    }
}
