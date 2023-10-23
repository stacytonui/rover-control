<?php

namespace App\Domain\Navigation;

use App\Domain\Exceptions\ObstacleEncounteredException;

class Position
{
    private World $world;
    private Coordinate $coordinate;
    private Direction $direction;

    /**
     * @param World $world
     * @param Coordinate $coordinate
     * @param Direction $direction
     */
    public function __construct(World $world, Coordinate $coordinate, Direction $direction)
    {
        $this->world = $world;
        $this->coordinate = $coordinate;
        $this->direction = $direction;
    }


    private function forward(): Coordinate
    {
        return match ($this->direction) {
            Direction::North => new Coordinate($this->coordinate->getX(), $this->coordinate->getY() + 1),
            Direction::East => new Coordinate($this->coordinate->getX() + 1, $this->coordinate->getY()),
            Direction::West => new Coordinate($this->coordinate->getX() - 1, $this->coordinate->getY()),
            Direction::South => new Coordinate($this->coordinate->getX(), $this->coordinate->getY() - 1),
        };
    }

    private function left(): Direction
    {
        return match ($this->direction) {
            Direction::North => Direction::West,
            Direction::East => Direction::North,
            Direction::West => Direction::South,
            Direction::South => Direction::East,
        };
    }

    private function right(): Direction
    {
        return match ($this->direction) {
            Direction::North => Direction::East,
            Direction::East => Direction::South,
            Direction::West => Direction::North,
            Direction::South => Direction::West,
        };
    }

    /**
     * @throws ObstacleEncounteredException
     */
    public function nextPosition(Command $command): Position
    {
        $nextPosition = match ($command) {
            Command::Forward => new Position($this->world, $this->forward(), $this->direction),
            Command::Left => new Position($this->world, $this->coordinate, $this->left()),
            Command::Right => new Position($this->world, $this->coordinate, $this->right()),
        };
        $this->world->validateCoordinateMovable($nextPosition->coordinate);
        return $nextPosition;
    }

    public function __toString(): string
    {
        $directionString = $this->direction->name;
        return "$this->coordinate heading $directionString in $this->world";
    }
}
