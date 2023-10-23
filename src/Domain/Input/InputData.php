<?php

namespace App\Domain\Input;

use App\Domain\Navigation\Coordinate;
use App\Domain\Navigation\Direction;

class InputData
{
    private int $worldSize;
    private array $obstacles;
    private Coordinate $roverPosition;
    private Direction $roverDirection;
    private array $roverCommands;

    /**
     * @param int $worldSize
     * @param array $obstacles
     * @param Coordinate $roverPosition
     * @param Direction $roverDirection
     * @param array $roverCommands
     */
    public function __construct(
        int        $worldSize,
        array      $obstacles,
        Coordinate $roverPosition,
        Direction  $roverDirection,
        array      $roverCommands
    )
    {
        $this->worldSize = $worldSize;
        $this->obstacles = $obstacles;
        $this->roverPosition = $roverPosition;
        $this->roverDirection = $roverDirection;
        $this->roverCommands = $roverCommands;
    }

    /**
     * @return int
     */
    public function getWorldSize(): int
    {
        return $this->worldSize;
    }

    /**
     * @return array
     */
    public function getObstacles(): array
    {
        return $this->obstacles;
    }

    /**
     * @return Coordinate
     */
    public function getRoverPosition(): Coordinate
    {
        return $this->roverPosition;
    }

    /**
     * @return Direction
     */
    public function getRoverDirection(): Direction
    {
        return $this->roverDirection;
    }

    /**
     * @return array
     */
    public function getRoverCommands(): array
    {
        return $this->roverCommands;
    }
}
