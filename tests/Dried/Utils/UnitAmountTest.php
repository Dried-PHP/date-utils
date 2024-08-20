<?php

declare(strict_types=1);

use Dried\Utils\Unit;
use Dried\Utils\UnitAmount;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnitAmountTest extends TestCase
{
    public static function getUnitAmounts(): array
    {
        return [
            [UnitAmount::microseconds(12), Unit::Microsecond, 12.0],
            [UnitAmount::milliseconds(1.5), Unit::Millisecond, 1.5],
            [UnitAmount::seconds(999_999_999_999), Unit::Second, 999_999_999_999.0],
            [UnitAmount::minutes(60), Unit::Minute, 60.0],
            [UnitAmount::hours(-1), Unit::Hour, -1.0],
            [UnitAmount::days(-365), Unit::Day, -365.0],
            [UnitAmount::weeks(0), Unit::Week, 0.0],
            [UnitAmount::months(13), Unit::Month, 13.0],
            [UnitAmount::quarters(INF), Unit::Quarter, INF],
            [UnitAmount::years(1), Unit::Year, 1.0],
            [UnitAmount::decades(0.2), Unit::Decade, 0.2],
            [UnitAmount::centuries(1_000), Unit::Century, 1_000.0],
            [UnitAmount::millennia(M_PI), Unit::Millennium, M_PI],
        ];
    }

    #[DataProvider('getUnitAmounts')]
    public function testName(UnitAmount $unitAmount, Unit $unit, float $amount): void
    {
        self::assertSame($unit, $unitAmount->unit);
        self::assertSame($amount, $unitAmount->amount);
    }
}
