<?php

use App\Domain\Exceptions\ObstacleEncounteredException;
use App\Domain\Input\InputHandler;
use App\Domain\Input\StandardInputGetter;
use App\Domain\Navigation\Position;
use App\Domain\Navigation\Rover;
use App\Domain\Navigation\World;

require_once '../../vendor/autoload.php';

$inputData = (new InputHandler(new StandardInputGetter()))->getInput();

$rover = new Rover(
    new Position(
        new World(
            $inputData->getWorldSize(),
            $inputData->getObstacles(),
        ),
        $inputData->getRoverPosition(),
        $inputData->getRoverDirection(),
    ),
);

foreach ($inputData->getRoverCommands() as $command) {
    try {
        $rover->move($command);
    } catch (ObstacleEncounteredException $e) {
        $message = $e->getMessage();
        echo "$message. $rover";
        break;
    }
}