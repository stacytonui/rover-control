<?php

namespace App\Domain\Input;

interface InputGetter
{
    function getInput(string $prompt): string;
}
