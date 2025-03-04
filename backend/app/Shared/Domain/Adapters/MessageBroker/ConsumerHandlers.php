<?php

declare(strict_types=1);

namespace App\Shared\Domain\Adapters\MessageBroker;

use App\Proposal\Infra\Consumers\CreateProposalConsumer;
use App\Proposal\Infra\Consumers\NotifyCustomerConsumer;
use App\Proposal\Infra\Consumers\MarkProposalCreatedConsumer;

final class ConsumerHandlers
{
    protected static array $handlers = [
        'proposals' => [
            'create-proposal' => [
                CreateProposalConsumer::class
            ],
            'made-proposal' => [
                MarkProposalCreatedConsumer::class
            ]
        ],
        'notify' => [
            'proposal-maded' => [
                NotifyCustomerConsumer::class
            ]
        ]
    ];

    public static function getHandlersByRoutingKey(string $messageKey, string $actionName): array
    {
        foreach (self::$handlers as $routingKey => $handlersList) {
            if ($messageKey !== $routingKey) {
                continue;
            }

            foreach ($handlersList as $action => $handlers) {
                if ($action !== $actionName) {
                    continue;
                }

                return $handlers;
            }
        }

        return [];
    }
}
