<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Facade;

use Closure;
use Duyler\EventBusCoroutine\Collector;

final class Coroutine
{
    private static Collector $collector;

    public function __construct(Collector $collector)
    {
        static::$collector = $collector;
    }

    public static function add(
        string           $actionId,
        string           $driver = '',
        string | Closure $callback = '',
        string | Closure $promise = '',
        array            $classMap = [],
        array            $providers = [],
    ): void {
        self::$collector->addCoroutine(
            new \Duyler\EventBusCoroutine\Dto\Coroutine(
                actionId: $actionId,
                driver: $driver,
                promise: $promise,
                callback: $callback,
                classMap: $classMap,
                providers: $providers,
            )
        );
    }
}
