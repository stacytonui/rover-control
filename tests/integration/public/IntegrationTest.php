<?php

namespace App\Domain\Input {
    class TestInputGetter implements InputGetter
    {

        private array $arguments;
        private int $index;

        /**
         * @param array $arguments
         */
        public function __construct(array $arguments)
        {
            $this->arguments = $arguments;
            $this->index = 0;
        }

        function getInput(string $prompt): string
        {
            return $this->arguments[$this->index++];
        }
    }
}

namespace IntegrationTest {

    use App\Domain\Exceptions\ObstacleEncounteredException;
    use App\Domain\Input\InputHandler;
    use App\Domain\Input\TestInputGetter;
    use App\Domain\Navigation\Coordinate;
    use App\Domain\Navigation\Position;
    use App\Domain\Navigation\Rover;
    use App\Domain\Navigation\World;
    use PHPUnit\Framework\TestCase;

    class IntegrationTest extends TestCase
    {

        /**
         * Integration Test for Rover Movement with Valid Commands
         *
         * This test case checks if a Rover can successfully execute a series of valid movement commands.
         *
         * Rover's Movement Details:
         * - Initial position: (0, 0)
         * - Initial direction: North
         * - Commands: FFF (Move Forward 3 times to (0,3)), R (Turn Right to face East), F (Move Forward to (1,4)),
         * - L (Turn Left to face North)
         * - Final position: (1, 3) heading North
         * - World size: 5x5
         * - Obstacles: (1, 2), (4, 3)
         *
         * @throws ObstacleEncounteredException If an obstacle is encountered during movement.
         */
        public function testRoverGivenValidCommandsShouldExecuteSuccessfully()
        {
            $arguments = [
                "5",
                "2",
                "0",
                "0",
                "N",
                "FFFRFL",
            ];
            $obstacles = [
                new Coordinate(1, 2),
                new Coordinate(4, 3),
            ];

            $inputData = (new InputHandler(new TestInputGetter($arguments)))->getInput();

            $rover = new Rover(
                new Position(
                    new World(
                        $inputData->getWorldSize(),
                        $obstacles,
                    ),
                    $inputData->getRoverPosition(),
                    $inputData->getRoverDirection(),
                ),
            );

            foreach ($inputData->getRoverCommands() as $command) {
                $rover->move($command);
            }

            $this->assertEquals(
                "Rover currently at (1, 3) heading North in World of size 5 with (1, 2), (4, 3) obstacles",
                (string)$rover,
            );
        }

        /**
         * Integration Test for Rover Movement with Invalid Commands
         *
         * This test case checks if a Rover throws an ObstacleEncounteredException when encountering an obstacle.
         *
         * Rover's Movement Details:
         * - Initial position: (0, 0)
         * - Initial direction: North
         * - Commands: FFF (Move Forward 3 times to (0,3)), R (Turn Right to face East),
         * - FFFF (Move forward 5 times to attempt to get to attempt to get to (4,4) but an obstacle is met at (4,3))
         * - Throw ObstacleEncounteredException
         * - Final position: (3, 3) heading East
         * - World size: 5x5
         * - Obstacles: (1, 2), (4, 3)
         *
         * @throws ObstacleEncounteredException If an obstacle is encountered during movement.
         */
        public function testRoverGivenInvalidCommandsShouldThrowException()
        {
            $arguments = [
                "5",
                "2",
                "0",
                "0",
                "N",
                "FFFRFFFF",
            ];
            $obstacles = [
                new Coordinate(1, 2),
                new Coordinate(4, 3),
            ];

            $inputData = (new InputHandler(new TestInputGetter($arguments)))->getInput();

            $rover = new Rover(
                new Position(
                    new World(
                        $inputData->getWorldSize(),
                        $obstacles,
                    ),
                    $inputData->getRoverPosition(),
                    $inputData->getRoverDirection(),
                ),
            );

            $this->expectException(ObstacleEncounteredException::class);
            foreach ($inputData->getRoverCommands() as $command) {
                $rover->move($command);
            }

            $this->assertEquals(
                "Rover currently at (3, 3) heading East in World of size 5 with (1, 2), (4, 3) obstacles",
                (string)$rover,
            );
        }
    }
}
