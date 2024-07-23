<?php

declare(strict_types=1);

namespace Dried\Utils;

enum RoundingMode
{
    case Floor;
    case RoundHalfUp;
    case RoundHalfDown;
    case RoundHalfEven;
    case RoundHalfOdd;
    case Ceil;
    case ClosestToZero;
    case FarthestToZero;
}
