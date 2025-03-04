<?php

declare(strict_types=1);

namespace App\Shared\Infra\Services\ProposalApi;

use Illuminate\Support\Facades\Http;

class MockProposalApi implements ProposalApi
{
    private string $baseUrl = 'https://util.devi.tools/api/v2/authorize';

    public function checkHttpStatus(): bool
    {
        $response = Http::get($this->baseUrl)->json();

        if (isset($response['status']) && $response['status'] === 'success') {
            return true;
        }

        return false;
    }
}
