<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Closure;

class Coroutine
{
    private static CoroutineCollection $collection;

    public function __construct(CoroutineCollection $collection)
    {
        self::$collection = $collection;
    }

    public static function add(
        string $actionId,
        Closure|string $callback,
        Closure|string $handler = '',
        array $classMap = [],
        array $providers = [],
        string $driver = 'pcntl',
    ) {
        self::$collection->add(new \Duyler\EventBusCoroutine\DTO\Coroutine(
            actionId: $actionId,
            callback: $callback,
            handler: $handler,
            classMap: $classMap,
            providers: $providers,
            driver: $driver
        ));
    }
}
