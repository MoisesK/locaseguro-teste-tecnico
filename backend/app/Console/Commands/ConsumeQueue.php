<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use App\Shared\Domain\Adapters\Contracts\LogSystem;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;
use App\Shared\Domain\Adapters\MessageBroker\ConsumerBase;
use App\Shared\Domain\Adapters\MessageBroker\ConsumerHandlers;

final class ConsumeQueue extends Command
{
    protected $signature = 'rabbitmq:consume {--queue=} {--routingKeys=}';
    protected $description = 'Connect in queue for consuming messages';
    private string $exchange = 'locaseguro';
    private string $queue;
    private array $routingKeys;

    public function __construct(
        private MessageBroker $messageBroker,
        private LogSystem $logSystem
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->queue = $this->option('queue');
        $this->routingKeys = explode(',', $this->option('routingKeys'));

        foreach ($this->routingKeys as $routingKey) {
            $this->messageBroker->prepareChannel($this->queue, $this->exchange, $routingKey);
        }

        $this->messageBroker->consume(fn (AMQPMessage $message) => $this->processMessage($message));

        $channel = $this->messageBroker->getChannel();
        while ($channel->is_consuming()) {
            $this->warn("[" . date('Y-m-d H:i:s') . "] Starting app consumer in queue '{$this->queue}'");
            $channel->wait();
        }
    }

    private function processMessage(AMQPMessage $message): void
    {
        $this->info("Message Received: {$message->getBody()} | Routing Key {$message->getRoutingKey()}");

        $messageBody = json_decode($message->getBody(), true);
        $handlers = ConsumerHandlers::getHandlersByRoutingKey($message->getRoutingKey(), trim($messageBody['action']));

        if (!empty($handlers)) {
            $this->info("Handlers Found for '" . trim($messageBody['action']) . "' action! Executing...");

            foreach ($handlers as $handlerClassName) {
                $this->info("Executing {$handlerClassName}...");

                /** @var ConsumerBase $handler */
                $handler = new $handlerClassName($message, $this->logSystem);
                $handler->execute();
            }

            $this->info("All handlers executed!");

            return;
        }

        $this->info("No handlers for Execute!");
        $message->nack(true);
    }
}
