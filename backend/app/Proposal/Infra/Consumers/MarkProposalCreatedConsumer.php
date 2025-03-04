<?php

declare(strict_types=1);

namespace App\Proposal\Infra\Consumers;

use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Infra\Services\ProposalApi\ProposalApi;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Domain\Adapters\MessageBroker\ConsumerBase;
use App\Shared\Domain\Adapters\MessageBroker\QueueMessage;
use App\Proposal\Domain\UseCase\MarkProposalCreated\InputData;
use App\Proposal\Domain\UseCase\MarkProposalCreated\MarkProposalCreated;

class MarkProposalCreatedConsumer extends ConsumerBase
{
    private MarkProposalCreated $useCase;
    private ProposalRepository $repo;
    private MessageBroker $queueSystem;
    private ProposalApi $service;

    public function construct(): void
    {
        $this->useCase = app()->make(MarkProposalCreated::class);
        $this->repo = app()->make(ProposalRepository::class);
        $this->queueSystem = app()->make(MessageBroker::class);
        $this->service = app()->make(ProposalApi::class);

    }

    public function handle(): void
    {
        $data = $this->messageBody;
        $proposal = $this->repo->findById($data['id']);

        if (!$this->service->checkHttpStatus()) {
            $this->logSystem->error('Proposal api service is offline');
            $this->queueSystem->publish(new QueueMessage(
                action: 'made-proposal',
                data: $proposal->toArray(),
                options: [
                    "routingKey" => 'proposals',
                    "exchangeName" => 'locaseguro'
                ]
            ));

            return;
        }

        $this->useCase->execute(new InputData($proposal));

        $this->queueSystem->publish(new QueueMessage(
            action: 'proposal-maded',
            data: $proposal->toArray(),
            options: [
                "routingKey" => 'notify',
                "exchangeName" => 'locaseguro'
            ]
        ));
    }
}
