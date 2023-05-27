<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\EventBus\Contract\State\StateMainSuspendHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBusCoroutine\DTO\Coroutine;
use Duyler\EventBusCoroutine\Coroutine as CoroutineFacade;

readonly class CoroutineStateHandlerMain implements StateMainSuspendHandlerInterface
{
    public function __construct(
        private CoroutineCollection $coroutineCollection,
        private CoroutineDriverProvider $driverProvider,
    ) {
        new CoroutineFacade($this->coroutineCollection);
        $path = dirname('__DIR__') . '/../config/coroutines/http.php';

        if (is_file($path)) {
            include $path;
        }
    }

    public function handle(StateMainSuspendService $stateService): void
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

            $stateService->resume($callback);
        } else {
            $stateService->resume(is_callable($value) ? $value() : $value);
        }
    }

    public function observed(): array
    {
        return [];
    }

    public function prepare(): void
    {
    }
}
