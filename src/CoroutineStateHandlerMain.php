<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\EventBus\Contract\State\StateMainSuspendHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBusCoroutine\Dto\Coroutine;

readonly class CoroutineStateHandlerMain implements StateMainSuspendHandlerInterface
{
    public function __construct(
        private CoroutineCollection $coroutineCollection,
        private CoroutineDriverProvider $driverProvider,
    ) {
    }

    public function getResume(StateMainSuspendService $stateService): mixed
    {
        /** @var Coroutine $coroutine */
        $coroutine = $this->coroutineCollection->where('actionId', $stateService->getActionId())->first();

        $value = $stateService->getValue();

        if ($coroutine AND empty($coroutine->handler) === false || is_callable($value)) {

            if (is_callable($coroutine->handler)) {
                $handler = $coroutine->handler;
            } elseif (class_exists($coroutine->handler)) {
                $stateService->container->bind($coroutine->classMap);
                $stateService->container->setProviders($coroutine->providers);
                $handler = $stateService->container->make($coroutine->handler);
            } else {
                $handler = $value;
            }

            $callback = is_callable($coroutine->callback)
                ? $coroutine->callback
                : $stateService->container->make($coroutine->callback);

            $driver = $this->driverProvider->get($coroutine->driver);
            $driver->process($handler, $value);

            return $callback;
        }

        return is_callable($value) ? $value() : $value;
    }

    public function prepare(): void
    {
    }
}
