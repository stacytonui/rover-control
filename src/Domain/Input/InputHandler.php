<?php
namespace App\Domain\Input;

use App\Domain\Navigation\Command;
use App\Domain\Navigation\Coordinate;
use App\Domain\Navigation\Direction;

class InputHandler
{
    private InputGetter $inputGetter;

    private int $worldSize;
    private array $obstacles;
    private Coordinate $roverPosition;
    private Direction $roverDirection;
    private array $roverCommands;

    public function __construct(InputGetter $inputGetter)
    {
        $this->inputGetter = $inputGetter;

        $this->worldSize = $this->getWorldSize();
        $this->obstacles = $this->getObstacles();
        $this->roverPosition = $this->getRoverPosition();
        $this->roverDirection = $this->getRoverDirection();
        $this->roverCommands = $this->getRoverCommands();

        $this->summarize();
    }

    public function getInput(): InputData
    {
        return new InputData(
            $this->worldSize,
            $this->obstacles,
            $this->roverPosition,
            $this->roverDirection,
            $this->roverCommands,
        );
    }

    private function getWorldSize(): int
    {
        $defaultWorldSize = 10;
        return $this->getInteger("Enter world size:", $defaultWorldSize, 1);
    }

    private function getObstacles(): array
    {
        $maximumNumberOfObstacles = ($this->worldSize ** 2) - 1;
        $defaultNumberOfObstacles = min(3, $maximumNumberOfObstacles);

        $numberOfObstacles = $this->getInteger(
            "Enter number of obstacles (Maximum $maximumNumberOfObstacles):",
            $defaultNumberOfObstacles,
            0,
            $maximumNumberOfObstacles,
        );

        $obstacles = $this->generateObstacles($numberOfObstacles, $this->worldSize);
        $this->printObstacles($obstacles);
        return $obstacles;
    }

    private function getRoverPosition(): Coordinate
    {
        $defaultXCoordinate = 0;
        $x = $this->getInteger(
            "Enter rover's initial X coordinate:",
            $defaultXCoordinate,
            0,
            $this->worldSize,
        );

        $defaultYCoordinate = 0;
        $y = $this->getInteger(
            "Enter rover's initial Y coordinate:",
            $defaultYCoordinate,
            0,
            $this->worldSize,
        );

        $coordinate = new Coordinate($x, $y);

        if (in_array($coordinate, $this->obstacles)) {
            echo "Rover cannot start from coordinate $coordinate, obstacle present.\n";
            return $this->getRoverPosition();
        }

        return $coordinate;
    }

    private function getRoverDirection(): Direction
    {
        $defaultDirection = Direction::North;
        return $this->getDirection(
            "Enter rover's initial direction ([N]orth, [E]ast, [W]est, [S]outh):",
            $defaultDirection,
        );
    }

    private function getRoverCommands(): array
    {
        $defaultCommands = [
            Command::Forward->value,
            Command::Forward->value,
            Command::Right->value,
            Command::Forward->value,
            Command::Left->value,
            Command::Forward->value,
        ];
        return $this->getCommands(
            "Enter series of commands to drive the rover ([F]orward, [R]ight, [L]eft) e.g. FFRL:",
            $defaultCommands,
        );
    }

    private function summarize(): void
    {
        $obstaclesString = implode(", ", $this->obstacles);
        $directionString = $this->roverDirection->name;
        $commandsString = implode(
            ", ",
            array_map(fn(Command $command): string => $command->name, $this->roverCommands)
        );

        echo "\n";
        echo "Initializing a world of size $this->worldSize with $obstaclesString obstacles.\n";
        echo "The Rover is initially at $this->roverPosition heading $directionString.\n";
        echo "The rover will be issued with $commandsString commands.\n";
        echo "\n";
    }

    private function printObstacles(array $obstacles): void
    {
        $obstaclesString = implode(", ", $obstacles);
        echo "Obstacles are at $obstaclesString. Plan the rover's route accordingly ;)\n";
    }

    private function generateObstacles(int $numberOfObstacles, int $worldSize): array
    {
        $obstacles = [];
        foreach (range(0, $numberOfObstacles - 1) as $ignored) {
            $obstacle = null;
            while (empty($obstacle) || in_array($obstacle, $obstacles)) {
                $obstacle = $this->randomCoordinate($worldSize - 1);
            }

            $obstacles[] = $obstacle;
        }
        return $obstacles;
    }

    private function randomCoordinate(int $bound): Coordinate
    {
        return new Coordinate(rand(0, $bound), rand(0, $bound));
    }

    private function getCommands(string $prompt, array $default): array
    {
        $validOptions = implode(array_map(fn(Command $command): string => $command->value, Command::cases()));
        $input = null;
        while (empty($input) || preg_match("/^[^$validOptions]+$/i", $input)) {
            $input = strtoupper($this->readline($prompt, implode($default)));
        }

        $commands = [];
        foreach (str_split($input) as $command) {
            $commands[] = Command::from($command);
        }

        return $commands;
    }

    private function getDirection(string $prompt, Direction $default): Direction
    {
        $input = null;
        while (empty($input)) {
            $input = Direction::tryFrom(strtoupper($this->readline($prompt, $default->value)));
        }
        return $input;
    }

    private function getInteger(string $prompt, int $default, int $minimum = 0, int $maximum = PHP_INT_MAX): int
    {
        $input = null;
        while (!is_numeric($input) || $input < $minimum || $input > $maximum) {
            $input = $this->readline($prompt, $default);
        }
        return (int)$input;
    }

    private function readline(string $prompt, $default): string
    {
        $input = $this->inputGetter->getInput("$prompt [$default] ");

        if (empty($input)) {
            return $default;
        }

        return $input;
    }
}
