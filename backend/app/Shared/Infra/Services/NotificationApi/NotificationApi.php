<?php

declare(strict_types=1);

namespace App\Shared\Infra\Services\NotificationApi;

interface NotificationApi
{
    public function checkHttpStatus(): bool;
}
