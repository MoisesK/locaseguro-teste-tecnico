<?php

declare(strict_types=1);

namespace App\Shared\Infra\Services\ProposalApi;

interface ProposalApi
{
    public function checkHttpStatus(): bool;
}
