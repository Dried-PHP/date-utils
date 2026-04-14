<?php

declare(strict_types=1);

namespace Tests\Dried\Utils;

use Closure;
use Dried\Utils\WeekDay;
use Dried\Utils\WeekDays;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WeekDaysTest extends TestCase
{
    public static function getCases(): array
    {
        return [
            'empty' => [
                [],
                static fn () => new WeekDays(),
            ],
            'all' => [
                [
                    WeekDay::Monday,
                    WeekDay::Tuesday,
                    WeekDay::Wednesday,
                    WeekDay::Thursday,
                    WeekDay::Friday,
                    WeekDay::Saturday,
                    WeekDay::Sunday,
                ],
                WeekDays::all(...),
            ],
            'mondayToFriday' => [
                [
                    WeekDay::Monday,
                    WeekDay::Tuesday,
                    WeekDay::Wednesday,
                    WeekDay::Thursday,
                    WeekDay::Friday,
                ],
                WeekDays::mondayToFriday(...),
            ],
            'weekend' => [
                [
                    WeekDay::Saturday,
                    WeekDay::Sunday,
                ],
                WeekDays::weekend(...),
            ],
            'allButTueAndFri' => [
                [
                    WeekDay::Monday,
                    WeekDay::Wednesday,
                    WeekDay::Thursday,
                    WeekDay::Saturday,
                    WeekDay::Sunday,
                ],
                static fn () => WeekDays::allBut(WeekDay::Tuesday, WeekDay::Friday),
            ],
        ];
    }

    #[DataProvider('getCases')]
    public function testIteration(array $result, Closure $builder): void
    {
        $set = $builder();

        self::assertInstanceOf(WeekDays::class, $set);
        self::assertSame($result, iterator_to_array($set));
    }
}
