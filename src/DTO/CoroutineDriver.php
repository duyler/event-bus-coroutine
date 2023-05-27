<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\DTO;

class CoroutineDriver
{
    public function __construct(
        public string $id,
        public string $class,
        public array  $classMap = [],
        public array  $providers = [],
    ) {
    }
}
