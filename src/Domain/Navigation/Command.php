<?php

namespace App\Domain\Navigation;

enum Command: string
{
    case Forward = 'F';
    case Left = 'L';
    case Right = 'R';
}
