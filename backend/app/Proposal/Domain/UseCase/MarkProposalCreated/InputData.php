<?php

declare(strict_types=1);

namespace App\Proposal\Domain\UseCase\MarkProposalCreated;

use App\Proposal\Domain\Entity\Proposal;

class InputData
{
    public function __construct(
        public Proposal $proposal
    ) {
    }
}
