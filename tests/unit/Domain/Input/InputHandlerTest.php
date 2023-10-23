<?php

use App\Domain\Input\InputGetter;
use App\Domain\Input\InputHandler;
use App\Domain\Navigation\Command;
use App\Domain\Navigation\Coordinate;
use App\Domain\Navigation\Direction;
use PHPUnit\Framework\TestCase;

class InputHandlerTest extends TestCase
{

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetInputWhenCalledShouldReturnInput()
    {
        $inputGetter = $this->createMock(InputGetter::class);
        $inputGetter->expects($this->exactly(6))
            ->method('getInput')
            ->willReturn(
                "5",
                "2",
                "1",
                "1",
                "E",
                "FF",
            );

        $inputHandler = new InputHandler($inputGetter);

        $inputData = $inputHandler->getInput();

        $this->assertEquals(5, $inputData->getWorldSize());
        $this->assertCount(2, $inputData->getObstacles());
        $this->assertEquals(new Coordinate(1, 1), $inputData->getRoverPosition());
        $this->assertEquals(Direction::East, $inputData->getRoverDirection());
        $this->assertEquals([Command::Forward, Command::Forward], $inputData->getRoverCommands());
    }
}