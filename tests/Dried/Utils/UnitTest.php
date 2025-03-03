<?php

declare(strict_types=1);

namespace Tests\Dried\Utils;

use DateInterval;
use Dried\Utils\Unit;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function testCases(): void
    {
        $cases = Unit::cases();

        self::assertIsArray($cases);
        self::assertCount(13, $cases);
    }

    public function testAllBut(): void
    {
        $cases = Unit::allBut(Unit::Year, Unit::Week);

        self::assertSame([
            Unit::Millennium,
            Unit::Century,
            Unit::Decade,
            Unit::Quarter,
            Unit::Month,
            Unit::Day,
            Unit::Hour,
            Unit::Minute,
            Unit::Second,
            Unit::Millisecond,
            Unit::Microsecond,
        ], $cases);
    }

    public function testTo(): void
    {
        $cases = Unit::Year->to(Unit::Second, [Unit::Quarter, Unit::Week]);

        self::assertSame([
            Unit::Year,
            Unit::Month,
            Unit::Day,
            Unit::Hour,
            Unit::Minute,
            Unit::Second,
        ], $cases);

        $cases = Unit::between(Unit::Second, Unit::Year, [Unit::Quarter, Unit::Week]);

        self::assertSame([
            Unit::Second,
            Unit::Minute,
            Unit::Hour,
            Unit::Day,
            Unit::Month,
            Unit::Year,
        ], $cases);
    }

    public static function getUnits(): array
    {
        return array_map(
            static fn (Unit $unit) => [$unit],
            Unit::cases(),
        );
    }

    #[DataProvider('getUnits')]
    public function testName(Unit $unit): void
    {
        self::assertSame($unit, Unit::fromName($unit->name));
        self::assertSame($unit, Unit::fromName($unit->value));
        self::assertSame($unit, Unit::tryFromName($unit->name));
        self::assertSame($unit, Unit::tryFromName($unit->value));
    }

    public static function getIntervalForUnit(): array
    {
        $micro = new DateInterval('PT1S');
        $micro->s = 0;
        $micro->f = 0.000_001;

        $milli = new DateInterval('PT1S');
        $milli->s = 0;
        $milli->f = 0.001;

        return [
            [Unit::Microsecond, $micro],
            [Unit::Millisecond, $milli],
            [Unit::Second, new DateInterval('PT1S')],
            [Unit::Minute, new DateInterval('PT1M')],
            [Unit::Hour, new DateInterval('PT1H')],
            [Unit::Day, new DateInterval('P1D')],
            [Unit::Week, new DateInterval('P7D')],
            [Unit::Month, new DateInterval('P1M')],
            [Unit::Quarter, new DateInterval('P3M')],
            [Unit::Year, new DateInterval('P1Y')],
            [Unit::Decade, new DateInterval('P10Y')],
            [Unit::Century, new DateInterval('P100Y')],
            [Unit::Millennium, new DateInterval('P1000Y')],
        ];
    }

    #[DataProvider('getIntervalForUnit')]
    public function testInterval(Unit $unit, DateInterval $interval): void
    {
        $result = $unit->interval();

        self::assertInstanceOf(DateInterval::class, $result);
        self::assertSame($interval->format('%R %y %m %d %H %i %s %f'), $result->format('%R %y %m %d %H %i %s %f'));
    }

    public function testInvalidInterval(): void
    {
        self::expectExceptionObject(new InvalidArgumentException(
            'Unable to create a DateInterval from INF Millennium',
        ));

        Unit::Millennium->interval(INF);
    }

    public static function getModifierForUnit(): array
    {
        return [
            [Unit::Microsecond, '1 Microsecond', '2 Microsecond'],
            [Unit::Millisecond, '1 Millisecond', '2 Millisecond'],
            [Unit::Second, '1 Second', '2 Second'],
            [Unit::Minute, '1 Minute', '2 Minute'],
            [Unit::Hour, '1 Hour', '2 Hour'],
            [Unit::Day, '1 Day', '2 Day'],
            [Unit::Week, '1 Week', '2 Week'],
            [Unit::Month, '1 Month', '2 Month'],
            [Unit::Quarter, '3 Month', '6 Month'],
            [Unit::Year, '1 Year', '2 Year'],
            [Unit::Decade, '10 Year', '20 Year'],
            [Unit::Century, '100 Year', '200 Year'],
            [Unit::Millennium, '1000 Year', '2000 Year'],
        ];
    }

    #[DataProvider('getModifierForUnit')]
    public function testModifier(Unit $unit, string $modifier, string $doubleModifier): void
    {
        self::assertSame($modifier, $unit->modifier());
        self::assertSame($doubleModifier, $unit->modifier(2));
    }

    public static function getPlurals(): array
    {
        return [
            ['microseconds', Unit::Microsecond],
            ['milliseconds', Unit::Millisecond],
            ['seconds', Unit::Second],
            ['minutes', Unit::Minute],
            ['hours', Unit::Hour],
            ['days', Unit::Day],
            ['weeks', Unit::Week],
            ['months', Unit::Month],
            ['quarters', Unit::Quarter],
            ['years', Unit::Year],
            ['decades', Unit::Decade],
            ['centuries', Unit::Century],
            ['millennia', Unit::Millennium],
        ];
    }

    #[DataProvider('getPlurals')]
    public function testPlural(string $result, Unit $unit): void
    {
        self::assertSame($result, $unit->plural());
    }
}
