<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

use Duyler\DependencyInjection\ContainerBuilder;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\EventBusCoroutine\Driver\PcntlDriver;
use Duyler\EventBusCoroutine\Dto\CoroutineDriver;
use Duyler\EventBusCoroutine\Exception\CoroutineDriverNotRegisteredException;

class CoroutineDriverProvider
{
    private const DEFAULT_DRIVER_CLASS = PcntlDriver::class;
    private const DEFAULT_DRIVER_ID = 'pcntl';

    private ContainerInterface $container;

    /** @var CoroutineDriver[] */
    private array $drivers = [];

    public function __construct()
    {
        $this->container = ContainerBuilder::build();

        $this->register(new CoroutineDriver(
            id: self::DEFAULT_DRIVER_ID,
            class: self::DEFAULT_DRIVER_CLASS,
        ));
    }

    public function get(string $id): CoroutineDriverInterface
    {
        $coroutineDriverData = $this->drivers[$id] ?? throw new CoroutineDriverNotRegisteredException($id);

        if ($this->container->has($coroutineDriverData->class) === false) {
            $this->container->setProviders($coroutineDriverData->providers);
            $this->container->bind($coroutineDriverData->classMap);
            $this->container->make($coroutineDriverData->class);
        }

        return $this->container->get($coroutineDriverData->class);
    }

    public function register(CoroutineDriver $coroutineDriver): void
    {
        $this->drivers[$coroutineDriver->id] = $coroutineDriver;
    }
}
