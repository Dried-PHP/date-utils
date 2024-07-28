<?php

declare(strict_types=1);

namespace Dried\Utils;

use DateInterval;

enum Unit: string
{
    case Microsecond = 'microsecond';
    case Millisecond = 'millisecond';
    case Second = 'second';
    case Minute = 'minute';
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Quarter = 'quarter';
    case Year = 'year';
    case Decade = 'decade';
    case Century = 'century';
    case Millennium = 'millennium';

    public static function fromName(string $unit): self
    {
        return self::from(strtolower($unit));
    }

    public static function tryFromName(string $unit): ?self
    {
        return self::tryFrom(strtolower($unit));
    }

    public function interval(int|float $value = 1): DateInterval
    {
        return match ($this) {
            self::Decade => DateInterval::createFromDateString(($value * 10) . ' years'),
            self::Century => DateInterval::createFromDateString(($value * 100) . ' years'),
            self::Millennium => DateInterval::createFromDateString(($value * 1000) . ' years'),
            default => DateInterval::createFromDateString("$value $this->name"),
        };
    }
}
