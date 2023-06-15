<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\Contract\PackageLoader\LoaderServiceInterface;
use Duyler\Contract\PackageLoader\PackageLoaderInterface;
use Duyler\Framework\Facade\Coroutine;

class Loader implements PackageLoaderInterface
{
    public function load(LoaderServiceInterface $loaderService): void
    {
        $container = $loaderService->getContainer();

        $collector = $container->make(Collector::class);

        new Coroutine($collector);

        $stateHandler = $container->make(CoroutineStateHandler::class);
        $loaderService->getBuilder()->addStateHandler($stateHandler);
    }
}
