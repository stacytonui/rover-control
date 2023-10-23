<?php

use App\Domain\Exceptions\ObstacleEncounteredException;
use PHPUnit\Framework\TestCase;
use App\Domain\Navigation\Rover;
use App\Domain\Navigation\Position;
use App\Domain\Navigation\Command;

class RoverTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ObstacleEncounteredException
     */
    public function testMoveGivenACommandShouldMoveRover()
    {
        $command = Command::Forward;
        $nextPosition = $this->createMock(Position::class);
        $nextPosition->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn("Next Position");

        $position = $this->createMock(Position::class);
        $position->expects($this->once())
            ->method('nextPosition')
            ->with($command)
            ->willReturn($nextPosition);

        $rover = new Rover($position);
        $rover->move($command);

        $this->assertEquals('Rover currently at Next Position', (string)$rover);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testMoveWhenObstacleEncounteredShouldThrowException()
    {
        $command = Command::Forward;

        $position = $this->createMock(Position::class);
        $position->expects($this->once())
            ->method('nextPosition')
            ->with($command)
            ->willThrowException(new ObstacleEncounteredException("Error"));

        $rover = new Rover($position);

        $this->expectException(ObstacleEncounteredException::class);
        $rover->move($command);
    }
}
