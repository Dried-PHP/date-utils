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
            [UnitAmount::microseconds(...), Unit::Microsecond],
            [UnitAmount::milliseconds(...), Unit::Millisecond],
            [UnitAmount::seconds(...), Unit::Second],
            [UnitAmount::minutes(...), Unit::Minute],
            [UnitAmount::hours(...), Unit::Hour],
            [UnitAmount::days(...), Unit::Day],
            [UnitAmount::weeks(...), Unit::Week],
            [UnitAmount::months(...), Unit::Month],
            [UnitAmount::quarters(...), Unit::Quarter],
            [UnitAmount::years(...), Unit::Year],
            [UnitAmount::decades(...), Unit::Decade],
            [UnitAmount::centuries(...), Unit::Century],
            [UnitAmount::millennia(...), Unit::Millennium],
        ];
    }

    #[DataProvider('getUnitAmounts')]
    public function testName(callable $unitAmountCreator, Unit $unit): void
    {
        $values = [12.0, 1.5, 999_999_999_999, 60, -1, -365, 0, 13, INF, 1, 0.2, 1_000, M_PI];

        foreach ($values as $value) {
            $unitAmount = $unitAmountCreator($value);
            self::assertInstanceOf(UnitAmount::class, $unitAmount);
            self::assertSame($unit, $unitAmount->unit);
            self::assertSame((float) $value, $unitAmount->amount);
        }
    }
}
