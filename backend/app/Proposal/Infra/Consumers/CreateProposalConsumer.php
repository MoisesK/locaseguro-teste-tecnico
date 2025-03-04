<?php

declare(strict_types=1);

namespace App\Proposal\Infra\Consumers;

use DateTime;
use App\Proposal\Domain\Entity\Payment;
use App\Proposal\Domain\Entity\Customer;
use App\Proposal\Domain\Entity\Proposal;
use App\Proposal\Domain\Contracts\ProposalRepository;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Domain\Adapters\MessageBroker\ConsumerBase;
use App\Shared\Domain\Adapters\MessageBroker\QueueMessage;

class CreateProposalConsumer extends ConsumerBase
{
    private ProposalRepository $repo;
    private MessageBroker $queueSystem;

    public function construct(): void
    {
        $this->repo = app()->make(ProposalRepository::class);
        $this->queueSystem = app()->make(MessageBroker::class);
    }

    public function handle(): void
    {
        $data = $this->messageBody;
        $proposal = new Proposal(
            customer: new Customer(
                name: $data['customer']['name'],
                cpf: $data['customer']['cpf'],
                birthDate: new DateTime($data['customer']['birthDate']),
                email: $data['customer']['email']
            ),
            payment: new Payment($data['payment']['amount'], $data['payment']['pixKey'])
        );

        $this->repo->create($proposal);

        $this->queueSystem->publish(new QueueMessage(
            action: 'made-proposal',
            data: $proposal->toArray(),
            options: [
                "routingKey" => 'proposals',
                "exchangeName" => 'locaseguro'
            ]
        ));
    }
}
