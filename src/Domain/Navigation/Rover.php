<?php

namespace App\Domain\Navigation;

use App\Domain\Exceptions\ObstacleEncounteredException;

class Rover
{

    private Position $position;

    /**
     * @param Position $position
     */
    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    /**
     * @throws ObstacleEncounteredException
     */
    public function move(Command $command): void
    {
        $commandString = $command->name;
        echo "Executing $commandString. ";

        $this->position = $this->position->nextPosition($command);
        echo "Rover moved to $this->position\n";
    }

    public function __toString(): string
    {
        return "Rover currently at $this->position";
    }
}