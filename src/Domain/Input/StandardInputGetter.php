<?php

namespace App\Domain\Input;

class StandardInputGetter implements InputGetter
{

    function getInput(string $prompt): string
    {
        echo $prompt;
        return trim(fgets(STDIN));
    }
}
