<?php

declare(strict_types=1);

namespace App\Shared\Infra\Adapters\LogSystem;

use Illuminate\Support\Facades\Log;
use App\Shared\Domain\Adapters\Contracts\LogSystem;

final class LaravelLog implements LogSystem
{
    public function debug(string $message, array $options = []): void
    {
        $channel = $options['channel'] ?? 'default';
        Log::channel($channel)->debug($message);
    }

    public function error(string $message, array $options = []): void
    {
        $channel = $options['channel'] ?? 'default';
        Log::channel($channel)->error($message);
    }

    public function warning(string $message, array $options = []): void
    {
        $channel = $options['channel'] ?? 'default';
        Log::channel($channel)->warning($message);
    }

    public function info(string $message, array $options = []): void
    {
        $channel = $options['channel'] ?? 'default';
        Log::channel($channel)->info($message);
    }
}
