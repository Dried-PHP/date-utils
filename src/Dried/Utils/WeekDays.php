<?php

declare(strict_types=1);

namespace Dried\Utils;

use IteratorAggregate;
use Traversable;

/** @implements IteratorAggregate<WeekDay> */
final readonly class WeekDays implements IteratorAggregate
{
    /** @var list<WeekDay> */
    private array $days;

    public function __construct(WeekDay ...$days)
    {
        $this->days = array_values($days);
    }

    /** @return Traversable<WeekDay> */
    public function getIterator(): Traversable
    {
        yield from $this->days;
    }

    public static function all(): self
    {
        return new self(
            WeekDay::Monday,
            WeekDay::Tuesday,
            WeekDay::Wednesday,
            WeekDay::Thursday,
            WeekDay::Friday,
            WeekDay::Saturday,
            WeekDay::Sunday,
        );
    }

    public static function mondayToFriday(): self
    {
        return new self(
            WeekDay::Monday,
            WeekDay::Tuesday,
            WeekDay::Wednesday,
            WeekDay::Thursday,
            WeekDay::Friday,
        );
    }

    public static function weekend(): self
    {
        return new self(
            WeekDay::Saturday,
            WeekDay::Sunday,
        );
    }

    public static function allBut(WeekDay ...$days): self
    {
        return self::all()->except(...$days);
    }

    public function except(WeekDay ...$days): self
    {
        $values = array_column($days, 'value');

        return new self(...array_filter(
            $this->days,
            static fn (WeekDay $day) => !\in_array($day->value, $values, true),
        ));
    }
}
