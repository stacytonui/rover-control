<?php

use App\Domain\Exceptions\ObstacleEncounteredException;
use PHPUnit\Framework\TestCase;
use App\Domain\Navigation\World;
use App\Domain\Navigation\Coordinate;

class WorldTest extends TestCase
{
    /**
     * @throws ObstacleEncounteredException
     */
    public function testValidateCoordinateMovableWhenCoordinateIsMovableShouldPassValidation()
    {
        $world = new World(
            10,
            [new Coordinate(1, 2), new Coordinate(6, 7), new Coordinate(3, 4)]
        );

        $world->validateCoordinateMovable(new Coordinate(2, 3));
        $this->expectNotToPerformAssertions();
    }

    public function testValidateCoordinateMovableWhenCoordinateIsOutOfBoundsShouldThrowException()
    {
        $world = new World(
            10,
            [new Coordinate(1, 2), new Coordinate(6, 7), new Coordinate(3, 4)]
        );

        $this->expectException(ObstacleEncounteredException::class);
        $world->validateCoordinateMovable(new Coordinate(10, 11));
    }

    public function testValidateCoordinateMovableWhenCoordinateHasObstacleShouldThrowException()
    {
        $world = new World(
            10,
            [new Coordinate(1, 2), new Coordinate(6, 7), new Coordinate(3, 4)]
        );

        $this->expectException(ObstacleEncounteredException::class);
        $world->validateCoordinateMovable(new Coordinate(6, 7));
    }
}
