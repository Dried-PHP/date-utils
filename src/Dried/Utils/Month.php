<?php

declare(strict_types=1);

namespace Dried\Utils;

use DateTimeImmutable;

enum Month: int
{
    case January = 1;
    case February = 2;
    case March = 3;
    case April = 4;
    case May = 5;
    case June = 6;
    case July = 7;
    case August = 8;
    case September = 9;
    case October = 10;
    case November = 11;
    case December = 12;

    public static function int(self|int|null $value): ?int
    {
        return $value instanceof self ? $value->value : $value;
    }

    public static function fromNumber(int $number): self
    {
        $month = $number % 12;

        return self::from($month + ($month < 1 ? 12 : 0));
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
                    isset($translations['months'][$day->value])
                    && $lower(rtrim($translations['weekdays'][$day->value], '.')) === $lowerName
                )
                || (
                    isset($translations['months_short'][$day->value])
                    && $lower(rtrim($translations['weekdays_short'][$day->value], '.')) === $lowerName
                )
            ) {
                return $day;
            }
        }

        throw new InvalidMonthException($name);
    }

    public function ofTheYear(DateTimeImmutable|int|null $now = null): DateTimeImmutable
    {
        if (\is_int($now)) {
            return DateTimeImmutable::createFromFormat('Y-n-j', "$now-$this->value-1");
        }

        $modifier = $this->name.' 1st';

        return $now?->modify($modifier) ?? new DateTimeImmutable($modifier);
    }
}
