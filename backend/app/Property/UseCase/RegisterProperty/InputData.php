<?php

declare(strict_types=1);

namespace App\Property\UseCase\RegisterProperty;

use App\Property\Domain\Entity\Property;

class InputData
{
    public function __construct(
        public Property $property
    ) {
    }
}
