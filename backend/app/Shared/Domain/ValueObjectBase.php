<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use JsonSerializable;
use App\Shared\Domain\Contracts\ValueObject;

abstract class ValueObjectBase implements ValueObject, JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function jsonSerialize(): mixed
    {
        return $this->value();
    }

    public function __get($name): bool|string
    {
        return $this->{$name};
    }
}
