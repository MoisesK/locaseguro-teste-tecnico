<?php

declare(strict_types=1);

namespace App\Shared\Infra\Exceptions;

class ValidationException extends ExceptionBase
{
    protected int|string $errorCode = 'VALIDATION_ERROR';

    public function getName(): string
    {
        return 'Validation Error';
    }
}
