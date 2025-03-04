<?php

declare(strict_types=1);

namespace App\Proposal\Domain\UseCase\CreateProposal;

use App\Shared\Infra\Exceptions\ValidationException;
use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Domain\Adapters\MessageBroker\QueueMessage;

class CreateProposal
{
    public function __construct(
        private ProposalRepository $repository,
        private MessageBroker $queueSystem
    ) {
    }

    public function execute(InputData $inputData): void
    {
        if (!$inputData->proposal->customer->cpf->isValid) {
            throw new ValidationException(['field' => 'cpf', 'error' => 'invalidCpf'], 'Ops, o documento digitado é inválido.');
        }

        if (!$inputData->proposal->customer->email->value()) {
            throw new ValidationException(['field' => 'email', 'error' => 'invalidEmail'], 'Ops, o email digitado é inválido.');
        }

        $this->queueSystem->publish(new QueueMessage(
            action: 'create-proposal',
            data: $inputData->proposal->toArray(),
            options: [
                "routingKey" => 'proposals',
                "exchangeName" => 'locaseguro'
            ]
        ));
    }
}
