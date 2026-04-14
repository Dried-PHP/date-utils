<?php

declare(strict_types=1);

namespace Dried\Utils;

use DateTimeImmutable;
use InvalidArgumentException;

enum WeekDay: int
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;

    public static function int(self|int|null $value): ?int
    {
        return $value instanceof self ? $value->value : $value;
    }

    public static function fromNumber(int $number): self
    {
        $day = $number % 7;

        return self::from($day + ($day < 0 ? 7 : 0));
    }

    public static function fromName(string $name, array $translations = []): self
    {
        static $lower = null;
        $lower ??= function_exists('mb_strtolower') ? mb_strtolower(...) : strtolower(...);
        $lowerName = $lower(rtrim($name, '.'));

        foreach (self::cases() as $day) {
            if (
                lcfirst($day->name) === $lowerName
                || (
                    isset($translations['weekdays'][$day->value])
                    && $lower(rtrim($translations['weekdays'][$day->value], '.')) === $lowerName
                )
                || (
                    isset($translations['weekdays_short'][$day->value])
                    && $lower(rtrim($translations['weekdays_short'][$day->value], '.')) === $lowerName
                )
                || (
                    isset($translations['weekdays_min'][$day->value])
                    && $lower(rtrim($translations['weekdays_min'][$day->value], '.')) === $lowerName
                )
            ) {
                return $day;
            }
        }

        throw new InvalidWeekDayException($name);
    }

    public function next(?DateTimeImmutable $now = null): DateTimeImmutable
    {
        return $now?->modify($this->name) ?? new DateTimeImmutable($this->name);
    }
}
