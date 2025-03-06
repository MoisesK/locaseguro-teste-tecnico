<?php

declare(strict_types=1);

namespace App\Property\Domain\Entity;

use DateTimeInterface;
use App\Shared\Domain\EntityBase;
use App\Shared\Domain\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\Email;

/**
 * @property string $name
 * @property Cpf $cpf
 * @property Email $email
 */
class Owner extends EntityBase
{
    public function __construct(
        protected string $name,
        protected string|Cpf $cpf,
        protected string|Email $email
    ) {
        $this->email = new Email($email);
        $this->cpf = new Cpf($cpf);
    }
}
