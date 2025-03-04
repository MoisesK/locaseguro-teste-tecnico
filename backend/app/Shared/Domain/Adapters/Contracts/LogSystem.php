<?php

declare(strict_types=1);

namespace App\Shared\Domain\Adapters\Contracts;

interface LogSystem
{
    public function debug(string $message, array $options = []): void;
    public function error(string $message, array $options = []): void;
    public function warning(string $message, array $options = []): void;
    public function info(string $message, array $options = []): void;
}
