<?php

declare(strict_types=1);

namespace Dried\Utils;

use DateInterval;
use InvalidArgumentException;

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

    public function modifier(int|float $value = 1): string
    {
        return match ($this) {
            self::Quarter => ($value * 3) . ' Month',
            self::Decade => ($value * 10) . ' Year',
            self::Century => ($value * 100) . ' Year',
            self::Millennium => ($value * 1000) . ' Year',
            default => "$value $this->name",
        };
    }

    public function interval(int|float $value = 1): DateInterval
    {
        return DateInterval::createFromDateString($this->modifier($value))
            ?: throw new InvalidArgumentException("Unable to create a DateInterval from $value $this->name");
    }

    public function plural(): string
    {
        return match ($this) {
            self::Century => 'centuries',
            self::Millennium => 'millennia',
            default => $this->value . 's',
        };
    }
}
