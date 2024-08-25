<?php

declare(strict_types=1);

namespace Dried\Utils;

final readonly class Rounding
{
    /**
     * Round following a given mode and by a given value (used as the unit to chunk).
     *
     * @example
     * ```
     * $rounding->roundBy(10.5); // 11
     * ```
     */
    public function roundInteger(float|int $number, RoundingMode $mode = RoundingMode::RoundHalfUp): int
    {
        return (int) $this->roundFloat($number, $mode);
    }

    /**
     * Round following a given mode and by a given value (used as the unit to chunk).
     *
     * @example
     * ```
     * // Round number of months up by quarter
     * $rounding->roundBy($numberOfMonths, RoundingMode::Ceil, 3);
     * ```
     */
    public function roundBy(
        float|int $number,
        RoundingMode $mode = RoundingMode::RoundHalfUp,
        float|int $by = 1,
    ): float {
        return $this->roundFloat($number / $by, $mode) * $by;
    }

    /**
     * Round following a given mode and with a certain number of decimals as precision.
     *
     * @example
     * ```
     * $numberOfMicroseconds = $rounding->roundBy($numberOfSecond, RoundingMode::Round, 6);
     * $numberOfSecond = $rounding->roundBy($numberOfMilliseconds, RoundingMode::Round, -3);
     * ```
     *
     * @infection-ignore-all
     */
    public function roundToDecimal(
        float|int $number,
        RoundingMode $mode = RoundingMode::RoundHalfUp,
        int $decimals = 0,
    ): float {
        return match ($mode) {
            RoundingMode::RoundHalfUp => round($number, precision: $decimals, mode: PHP_ROUND_HALF_UP),
            RoundingMode::RoundHalfDown => round($number, precision: $decimals, mode: PHP_ROUND_HALF_DOWN),
            RoundingMode::RoundHalfEven => round($number, precision: $decimals, mode: PHP_ROUND_HALF_EVEN),
            RoundingMode::RoundHalfOdd => round($number, precision: $decimals, mode: PHP_ROUND_HALF_ODD),
            default => $this->roundBy($number, $mode, pow(10, -$decimals)),
        };
    }

    private function roundFloat(float|int $number, RoundingMode $mode): float
    {
        return match ($mode) {
            RoundingMode::Ceil => ceil($number),
            RoundingMode::RoundHalfUp => round($number, mode: PHP_ROUND_HALF_UP),
            RoundingMode::RoundHalfDown => round($number, mode: PHP_ROUND_HALF_DOWN),
            RoundingMode::RoundHalfEven => round($number, mode: PHP_ROUND_HALF_EVEN),
            RoundingMode::RoundHalfOdd => round($number, mode: PHP_ROUND_HALF_ODD),
            RoundingMode::Floor => floor($number),
            RoundingMode::ClosestToZero => $number < 0 ? ceil($number) : floor($number),
            RoundingMode::FarthestToZero => $number < 0 ? floor($number) : ceil($number),
        };
    }
}
