<?php

namespace App\Domain\Navigation;

use App\Domain\Exceptions\ObstacleEncounteredException;

class World
{
    private int $size;
    private array $obstacles;

    /**
     * @param int $size
     * @param array $obstacles
     */
    public function __construct(int $size, array $obstacles)
    {
        $this->size = $size;
        $this->obstacles = $obstacles;
    }

    /**
     * @throws ObstacleEncounteredException
     */
    public function validateCoordinateMovable(Coordinate $coordinate): void
    {
        $this->validateCoordinateWithinBounds($coordinate);
        $this->validateCoordinateNotOnObstacle($coordinate);
    }

    /**
     * @throws ObstacleEncounteredException
     */
    private function validateCoordinateWithinBounds(Coordinate $coordinate): void
    {
        $withinBounds = ($coordinate->getX() >= 0 && $coordinate->getX() < ($this->size))
            && ($coordinate->getY() >= 0 && $coordinate->getY() < ($this->size));
        if (!$withinBounds) {
            throw new ObstacleEncounteredException(
                "Obstacle encountered! Coordinate $coordinate is out of world bounds",
            );
        }
    }

    /**
     * @throws ObstacleEncounteredException
     */
    private function validateCoordinateNotOnObstacle(Coordinate $coordinate): void
    {
        $onObstacle = in_array($coordinate, $this->obstacles);
        if ($onObstacle) {
            throw new ObstacleEncounteredException(
                "Obstacle encountered! Obstacle found at $coordinate",
            );
        }
    }

    public function __toString(): string
    {
        $obstaclesString = implode(", ", $this->obstacles);
        return "World of size $this->size with $obstaclesString obstacles";
    }
}
