<?php

declare(strict_types=1);

namespace Dried\Utils;

use InvalidArgumentException;
use Throwable;

final class InvalidWeekDayException extends InvalidArgumentException
{
    public function __construct(
        private readonly string $name,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            "'$name' is not a valid week day.",
            $code,
            $previous,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
