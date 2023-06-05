<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\EventBusCoroutine\Dto\Coroutine;
use Duyler\EventBusCoroutine\Dto\CoroutineDriver;

readonly class Collector
{
    public function __construct(
        private CoroutineCollection     $coroutineCollection,
        private CoroutineDriverProvider $coroutineDriverProvider,
    ) {
    }

    public function addCoroutine(Coroutine $coroutine): static
    {
        $this->coroutineCollection->add($coroutine);
        return $this;
    }

    public function addCoroutineDriver(CoroutineDriver $coroutineDriver): static
    {
        $this->coroutineDriverProvider->register($coroutineDriver);
        return $this;
    }
}
