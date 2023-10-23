<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Navigation\Position;
use App\Domain\Navigation\World;
use App\Domain\Navigation\Coordinate;
use App\Domain\Navigation\Direction;
use App\Domain\Navigation\Command;
use App\Domain\Exceptions\ObstacleEncounteredException;

class PositionTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ObstacleEncounteredException
     * @dataProvider nextPositionScenarios
     */
    public function testNextPositionGivenCommandShouldGenerateNextPosition(
        Direction  $initialDirection,
        Command    $command,
        Coordinate $expectedCoordinate,
        Direction  $expectedDirection,
    )
    {
        $worldMock = $this->createMock(World::class);
        $position = new Position($worldMock, new Coordinate(0, 0), $initialDirection);

        $worldMock->expects($this->once())
            ->method('validateCoordinateMovable')
            ->with($expectedCoordinate);

        $this->assertEquals(
            new Position($worldMock, $expectedCoordinate, $expectedDirection),
            $position->nextPosition($command),
        );
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testNextPositionWhenPositionIsNotMovableShouldThrowException()
    {
        $worldMock = $this->createMock(World::class);
        $worldMock->expects($this->once())
            ->method('validateCoordinateMovable')
            ->with(new Coordinate(-1, 0))
            ->willThrowException(new ObstacleEncounteredException("Error"));

        $position = new Position($worldMock, new Coordinate(0, 0), Direction::West);
        $this->expectException(ObstacleEncounteredException::class);

        $position->nextPosition(Command::Forward);
    }

    public static function nextPositionScenarios(): array
    {
        return [
            [Direction::North, Command::Forward, new Coordinate(0, 1), Direction::North],
            [Direction::North, Command::Left, new Coordinate(0, 0), Direction::West],
            [Direction::North, Command::Right, new Coordinate(0, 0), Direction::East],

            [Direction::East, Command::Forward, new Coordinate(1, 0), Direction::East],
            [Direction::East, Command::Left, new Coordinate(0, 0), Direction::North],
            [Direction::East, Command::Right, new Coordinate(0, 0), Direction::South],

            [Direction::West, Command::Forward, new Coordinate(-1, 0), Direction::West],
            [Direction::West, Command::Right, new Coordinate(0, 0), Direction::North],
            [Direction::West, Command::Left, new Coordinate(0, 0), Direction::South],

            [Direction::South, Command::Forward, new Coordinate(0, -1), Direction::South],
            [Direction::South, Command::Right, new Coordinate(0, 0), Direction::West],
            [Direction::South, Command::Left, new Coordinate(0, 0), Direction::East],
        ];
    }
}
