<?php

declare(strict_types=1);

namespace Duyler\EventBusCoroutine\Exception;

use Exception;

class CoroutineDriverNotAvailableException extends Exception
{
    public function __construct(string $driver)
    {
        $message = 'Coroutine driver ' . $driver . ' is not available in your PHP interpreter';
        parent::__construct($message);
    }
}
