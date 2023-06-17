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
        string           $driver,
        string | Closure $handler = '',
        string | Closure $callback = '',
        array            $classMap = [],
        array            $providers = [],
    ): void {
        self::$collector->addCoroutine(
            new \Duyler\EventBusCoroutine\Dto\Coroutine(
                actionId: $actionId,
                driver: $driver,
                callback: $callback,
                handler: $handler,
                classMap: $classMap,
                providers: $providers,
            )
        );
    }
}
