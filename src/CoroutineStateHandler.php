<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\DependencyInjection\ContainerInterface;
use Duyler\EventBus\Contract\State\StateMainSuspendHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBusCoroutine\Dto\Coroutine;

readonly class CoroutineStateHandler implements StateMainSuspendHandlerInterface
{
    public function __construct(
        private CoroutineCollection $coroutineCollection,
        private CoroutineDriverProvider $driverProvider,
        private Config $config,
    ) {
    }

    public function getResume(StateMainSuspendService $stateService): mixed
    {
        $value = $stateService->getValue();

        /** @var false|Coroutine $coroutine */
        $coroutine = $this->coroutineCollection->where('actionId', $stateService->getActionId())->first();

        if (empty($value) || is_callable($value) === false && empty($coroutine?->callback)) {
            return $value;
        }

        if ($value instanceof Coroutine) {
            $coroutine = $value;
        }

        if (is_callable($value)) {
            $coroutine = new Coroutine(
                driver: $coroutine?->driver ?? $this->config->defaultDriver,
                promise: $coroutine?->promise ?? '',
                callback: $value,
                classMap: $coroutine?->classMap ?? [],
                providers: $coroutine?->providers ?? [],
            );
        }

        $this->prepareContainer($stateService->container, $coroutine);

        $callback = is_callable($coroutine->callback)
            ? $coroutine->callback
            : $stateService->container->make($coroutine->callback);

        $result = $this->driverProvider->get($coroutine->driver)?->process($callback, $value);

        return match (true) {
            $result !== null || empty($coroutine->promise) => $result,
            is_callable($coroutine->promise) => $coroutine->promise,
            default => $stateService->container->make($coroutine->promise)
        };
    }

    private function prepareContainer(ContainerInterface $container, Coroutine $coroutine): void
    {
        $container->bind($coroutine->classMap);
        $container->setProviders($coroutine->providers);
    }

    public function prepare(): void
    {
    }
}
