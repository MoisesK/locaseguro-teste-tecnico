<?php

declare(strict_types=1);

namespace App\Proposal\Domain\Contracts;

use App\Proposal\Domain\Entity\Proposal;

interface ProposalRepository
{
    public function findById(int $id): ?Proposal;
    public function create(Proposal $proposal): void;
    public function update(Proposal $proposal): void;
}
