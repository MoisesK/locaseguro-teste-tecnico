<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use DateTimeInterface;

class EntityBase
{
    protected ?int $id = null;

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            if ($value instanceof EntityBase) {
                $array[$key] = $value->toArray();
                continue;
            }

            if ($value instanceof DateTimeInterface) {
                $array[$key] = $value->format('Y-m-d H:i:s');
                continue;
            }

            if ($value instanceof ValueObjectBase) {
                $array[$key] = $value->value();
                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }
}
