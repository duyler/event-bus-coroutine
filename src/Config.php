<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine;

final readonly class Config
{
    public function __construct(public string $defaultDriver)
    {
    }
}
