<?php

declare(strict_types=1);

namespace App\Proposal\Domain\Entity;

use App\Shared\Domain\EntityBase;

/**
 * @property string $amount
 * @property string $pixKey
 */
class Payment extends EntityBase
{
    public function __construct(
        protected string|int $amount,
        protected string $pixKey
    ) {
    }
}
