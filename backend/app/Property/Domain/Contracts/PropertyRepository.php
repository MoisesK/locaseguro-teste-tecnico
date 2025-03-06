<?php

declare(strict_types=1);

namespace App\Property\Domain\Contracts;

use App\Property\Domain\Entity\Owner;
use App\Property\Domain\Entity\Property;

interface PropertyRepository
{
    public function getAll(): array;
    public function findOwnerByField(string $field, mixed $value): ?Owner;
    public function create(Property $property): void;
}
