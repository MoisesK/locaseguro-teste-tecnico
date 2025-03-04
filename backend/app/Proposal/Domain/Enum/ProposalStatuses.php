<?php

declare(strict_types=1);

namespace App\Proposal\Domain\Enum;

enum ProposalStatuses: int
{
    case PENDING = 1;
    case CREATED = 2;
    case NOTIFYED = 3;
}
