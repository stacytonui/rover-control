<?php

namespace App\Domain\Exceptions;

use Exception;

class ObstacleEncounteredException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
