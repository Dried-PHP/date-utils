<?php

declare(strict_types=1);

namespace Tests\Dried\Utils;

use Dried\Utils\Rounding;
use Dried\Utils\RoundingMode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RoundingTest extends TestCase
{
    public static function getRoundByCases(): array
    {
        return [
            [12, 10, RoundingMode::Ceil, 3],
            [5.75, 5.84, RoundingMode::Floor, 0.25],
            [11, 10.5, RoundingMode::RoundHalfUp, 1],
        ];
    }

    #[DataProvider('getRoundByCases')]
    public function testRoundBy(float $result, float|int $number, RoundingMode $mode, float|int $by): void
    {
        $rounding = new Rounding();

        self::assertSame($result, $rounding->roundBy($number, $mode, $by));
    }

    public static function getRoundToDecimalCases(): array
    {
        return [
            [12_000_000, 12_123_456, RoundingMode::RoundHalfDown, -6],
            [12.123_457, 12.123_456_789, RoundingMode::RoundHalfDown, 6],
            [12.123_457, 12.123_456_789, RoundingMode::RoundHalfUp, 6],
            [12.123_456, 12.123_456_5, RoundingMode::RoundHalfDown, 6],
            [12.123_457, 12.123_456_5, RoundingMode::RoundHalfUp, 6],
            [12.123_456, 12.123_456_5, RoundingMode::RoundHalfEven, 6],
            [12.123_456, 12.123_456_5, RoundingMode::RoundHalfEven, 6],
            [12.122, 12.122_5, RoundingMode::RoundHalfEven, 3],
            [12.123, 12.123_5, RoundingMode::RoundHalfOdd, 3],
            [12.124, 12.124_5, RoundingMode::RoundHalfEven, 3],
            [12.125, 12.125_5, RoundingMode::RoundHalfOdd, 3],
        ];
    }

    #[DataProvider('getRoundToDecimalCases')]
    public function testRoundToDecimal(float $result, float|int $number, RoundingMode $mode, int $decimals): void
    {
        $rounding = new Rounding();

        self::assertSame($result, $rounding->roundToDecimal($number, $mode, $decimals));
    }
}
