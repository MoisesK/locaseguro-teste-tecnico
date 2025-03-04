<?php

declare(strict_types=1);

namespace App\Proposal\Domain\UseCase\MarkProposalCreated;

use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Domain\Adapters\MessageBroker\QueueMessage;

class MarkProposalCreated
{
    public function __construct(
        private ProposalRepository $repository,
        private MessageBroker $queueSystem
    ) {
    }

    public function execute(InputData $inputData): void
    {
        $inputData->proposal->markCreated();

        $this->repository->update($inputData->proposal);
    }
}
