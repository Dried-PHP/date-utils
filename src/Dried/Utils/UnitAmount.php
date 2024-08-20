<?php

declare(strict_types=1);

namespace Dried\Utils;

final readonly class UnitAmount
{
    public function __construct(
        public Unit $unit,
        public float $amount,
    ) {
    }

    public static function microseconds(int|float $amount): self
    {
        return new self(Unit::Microsecond, $amount);
    }

    public static function milliseconds(int|float $amount): self
    {
        return new self(Unit::Millisecond, $amount);
    }

    public static function seconds(int|float $amount): self
    {
        return new self(Unit::Second, $amount);
    }

    public static function minutes(int|float $amount): self
    {
        return new self(Unit::Minute, $amount);
    }

    public static function hours(int|float $amount): self
    {
        return new self(Unit::Hour, $amount);
    }

    public static function days(int|float $amount): self
    {
        return new self(Unit::Day, $amount);
    }

    public static function weeks(int|float $amount): self
    {
        return new self(Unit::Week, $amount);
    }

    public static function months(int|float $amount): self
    {
        return new self(Unit::Month, $amount);
    }

    public static function quarters(int|float $amount): self
    {
        return new self(Unit::Quarter, $amount);
    }

    public static function years(int|float $amount): self
    {
        return new self(Unit::Year, $amount);
    }

    public static function decades(int|float $amount): self
    {
        return new self(Unit::Decade, $amount);
    }

    public static function centuries(int|float $amount): self
    {
        return new self(Unit::Century, $amount);
    }

    public static function millennia(int|float $amount): self
    {
        return new self(Unit::Millennium, $amount);
    }
}
