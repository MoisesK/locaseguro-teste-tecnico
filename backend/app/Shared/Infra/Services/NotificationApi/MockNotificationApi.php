<?php

declare(strict_types=1);

namespace App\Shared\Infra\Services\NotificationApi;

use Illuminate\Support\Facades\Http;

class MockNotificationApi implements NotificationApi
{
    private string $baseUrl = 'https://util.devi.tools/api/v1/notify';

    public function checkHttpStatus(): bool
    {
        $response = Http::post($this->baseUrl);

        return $response->getStatusCode() === 204;
    }
}
