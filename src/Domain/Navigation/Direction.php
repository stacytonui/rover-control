<?php

namespace App\Domain\Navigation;
enum Direction: string
{
    case North = 'N';
    case East = 'E';
    case West = 'W';
    case South = 'S';
}
