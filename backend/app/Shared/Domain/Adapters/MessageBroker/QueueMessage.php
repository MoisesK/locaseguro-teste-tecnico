<?php

declare(strict_types=1);

namespace App\Shared\Domain\Adapters\MessageBroker;

final class QueueMessage
{
    public function __construct(
        public readonly string $action,
        public readonly array $data,
        protected readonly array $options = []
    ) {
    }

    public function getOption(string|int $optionKey, mixed $defaultValue = null): mixed
    {
        if (!isset($this->options[$optionKey])) {
            return $defaultValue;
        }

        return $this->options[$optionKey];
    }

    public function __toString(): string
    {
        $dataCloned = $this->data;
        $actionName = is_string($this->action) ? $this->action : $this->action->value;
        $processed = $dataCloned['processed'] ?? null;
        $createdAt = $dataCloned['createdAt'] ?? null;

        unset(
            $dataCloned['eventId'],
            $dataCloned['processed'],
            $dataCloned['eventName'],
            $dataCloned['userId']
        );

        return json_encode([
            'processed' => $processed,
            'action' => $actionName,
            'createdAt' => $createdAt,
            'data' => $dataCloned
        ]);
    }
}
