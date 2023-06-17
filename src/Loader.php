<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\Contract\PackageLoader\LoaderServiceInterface;
use Duyler\Contract\PackageLoader\PackageLoaderInterface;
use Duyler\EventBusCoroutine\Facade\Coroutine;
use Duyler\EventBusCoroutine\Facade\CoroutineDriver;

class Loader implements PackageLoaderInterface
{
    public function load(LoaderServiceInterface $loaderService): void
    {
        $container = $loaderService->getContainer();

        $driverProvider = $container->make(CoroutineDriverProvider::class);
        $collector = $container->make(Collector::class);

        new CoroutineDriver($driverProvider);
        new Coroutine($collector);

        $stateHandler = $container->make(CoroutineStateHandler::class);
        $loaderService->getBuilder()->addStateHandler($stateHandler);
    }
}
