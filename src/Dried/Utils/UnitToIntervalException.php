<?php

declare(strict_types=1);

namespace Dried\Utils;

use InvalidArgumentException;
use Throwable;

final class UnitToIntervalException extends InvalidArgumentException
{
    public function __construct(
        private readonly int|float $value,
        private readonly string $unitName,
        private readonly string $modifier,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            "Unable to create a DateInterval from $value $unitName",
            $code,
            $previous,
        );
    }

    public function getUnitName(): string
    {
        return $this->unitName;
    }

    public function getValue(): int|float
    {
        return $this->value;
    }

    public function getModifier(): string
    {
        return $this->modifier;
    }
}
