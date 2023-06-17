<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\DependencyInjection\ContainerInterface;
use Duyler\EventBus\Contract\State\StateMainSuspendHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBusCoroutine\Dto\Coroutine;
use RuntimeException;

readonly class CoroutineStateHandler implements StateMainSuspendHandlerInterface
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

        if ($coroutine || is_callable($value)) {

            $handler = match (true) {
                is_callable($value) => $value,
                is_callable($coroutine->handler) => $coroutine->handler,
                class_exists($coroutine->handler) => $this->prepareContainer($stateService->container, $coroutine)
                    ->make($coroutine->handler),
                default => throw new RuntimeException(
                    'Coroutine handler is not resolved for ' . $stateService->getActionId()
                )
            };

            $result = $this->driverProvider->get($coroutine->driver)?->process($handler, $value);

            return match (true) {
                $result !== null => $result,
                empty($coroutine->callback) => $coroutine->callback,
                is_callable($coroutine->callback) => $coroutine->callback,
                class_exists($coroutine->callback) => $this->prepareContainer($stateService->container, $coroutine)
                    ->make($coroutine->callback),
                default => throw new RuntimeException(
                    'Coroutine callback is not resolved for ' . $stateService->getActionId()
                )
            };
        }

        return is_callable($value) ? $value() : $value;
    }

    private function prepareContainer(ContainerInterface $container, Coroutine $coroutine): ContainerInterface
    {
        $container->bind($coroutine->classMap);
        $container->setProviders($coroutine->providers);
        return $container;
    }

    public function prepare(): void
    {
    }
}
