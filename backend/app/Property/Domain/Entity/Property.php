<?php

declare(strict_types=1);

namespace App\Property\Domain\Entity;

use App\Shared\Domain\EntityBase;

/**
 * @property Owner $owner
 */
class Property extends EntityBase
{
    public function __construct(
        protected Owner $owner,
        protected string $city,
        protected string $number,
        protected string $street,
        protected string $zipCode,
        protected int|string $amount,
    ) {
    }
}
