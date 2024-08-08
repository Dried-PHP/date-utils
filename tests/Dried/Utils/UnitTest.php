<?php

declare(strict_types=1);

namespace Tests\Dried\Utils;

use DateInterval;
use Dried\Utils\Unit;
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
