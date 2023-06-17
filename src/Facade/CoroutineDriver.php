<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Facade;

use Duyler\EventBusCoroutine\CoroutineDriverProvider;

final class CoroutineDriver
{
    private static CoroutineDriverProvider $coroutineDriverProvider;

    public function __construct(CoroutineDriverProvider $coroutineDriverProvider)
    {
        static::$coroutineDriverProvider = $coroutineDriverProvider;
    }

    public static function register(
        string $id,
        string $class,
        array  $classMap = [],
        array  $providers = [],
    ): void {
        self::$coroutineDriverProvider->register(
            new \Duyler\EventBusCoroutine\Dto\CoroutineDriver(
                id: $id,
                class: $class,
                classMap: $classMap,
                providers:   $providers,
            )
        );
    }
}
