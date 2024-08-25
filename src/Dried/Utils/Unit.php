<?php

declare(strict_types=1);

namespace Dried\Utils;

use DateInterval;
use InvalidArgumentException;

enum Unit: string
{
    case Millennium = 'millennium';
    case Century = 'century';
    case Decade = 'decade';
    case Year = 'year';
    case Quarter = 'quarter';
    case Month = 'month';
    case Week = 'week';
    case Day = 'day';
    case Hour = 'hour';
    case Minute = 'minute';
    case Second = 'second';
    case Millisecond = 'millisecond';
    case Microsecond = 'microsecond';

    public static function fromName(string $unit): self
    {
        return self::from(strtolower($unit));
    }

    public static function tryFromName(string $unit): ?self
    {
        return self::tryFrom(strtolower($unit));
    }

    /** @return list<self> */
    public static function allBut(self ...$omittedUnits): array
    {
        return self::unitsWithout(self::cases(), $omittedUnits);
    }

    /**
     * @param list<self> $skip
     *
     * @return list<self>
     */
    public static function between(Unit $from, Unit $to, array $skip): array
    {
        $units = [];
        $fromMet = false;
        $toMet = false;
        $toMetFirst = false;

        foreach (self::cases() as $unit) {
            if ($unit === $from) {
                $fromMet = true;
                $units[] = $unit;

                if ($toMet) {
                    break;
                }

                continue;
            }

            if ($unit === $to) {
                $toMet = true;
                $units[] = $unit;

                if ($fromMet) {
                    break;
                }

                $toMetFirst = true;

                continue;
            }

            if ($fromMet || $toMet) {
                $units[] = $unit;
            }
        }

        return self::unitsWithout($toMetFirst ? array_reverse($units) : $units, $skip);
    }

    /**
     * @param list<self> $skip
     *
     * @return list<self>
     */
    public function to(Unit $to, array $skip): array
    {
        return self::between($this, $to, $skip);
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

    /**
     * @param list<self> $units
     * @param list<self> $omittedUnits
     *
     * @return list<self>
     */
    private static function unitsWithout(array $units, array $omittedUnits): array
    {
        return array_values(array_udiff(
            $units,
            $omittedUnits,
            static fn (self $a, self $b) => $a->value <=> $b->value,
        ));
    }
}
