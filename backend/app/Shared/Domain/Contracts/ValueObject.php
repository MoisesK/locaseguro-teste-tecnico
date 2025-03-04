<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface ValueObject
{
    /**
     * Raw value object value
     * @return string|int|float|bool
     */
    public function value(): string|int|float|bool;

    /**
     * Method to return a object as string
     * @return string
     */
    public function __toString(): string;
}
