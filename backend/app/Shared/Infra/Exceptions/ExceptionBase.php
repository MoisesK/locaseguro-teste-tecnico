<?php

declare(strict_types=1);

namespace App\Shared\Infra\Exceptions;

use Exception;
use App\Shared\Domain\Exceptions\Error;

abstract class ExceptionBase extends Exception implements Error
{
    protected array $details = [];
    protected int | string $errorCode;

    public function __construct(array $details, string $message = '', ?int $code = 400, Exception $previous = null)
    {
        parent::__construct($message ?: 'Application Error', $code, $previous);
        $this->details = $details;
        $this->code = $this->errorCode ?? $code;
    }

    public function details(): array
    {
        return $this->details;
    }

    public function getName(): string
    {
        return 'Generic Error';
    }
}
