<?php

declare(strict_types=1);

namespace App\Shared\Domain\Adapters\MessageBroker;

use Closure;
use Exception;
use Throwable;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Shared\Domain\Adapters\Contracts\LogSystem;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use App\Shared\Domain\Adapters\Contracts\MessageBroker;

final class RabbitMqAdapter implements MessageBroker
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private string $exchangeName;
    private string $queueName;
    private string $routingKey;

    public function __construct(
        private LogSystem $logSystem
    ) {
    }

    private function connect(): void
    {
        if (isset($this->connection)) {
            if (!isset($this->channel) || !$this->channel->is_open()) {
                $this->channel = $this->connection->channel();
            }

            return;
        }

        while (!isset($this->connection)) {
            try {
                $this->connection = new AMQPStreamConnection(
                    host: $_ENV['RABBITMQ_HOST'],
                    port: $_ENV['RABBITMQ_PORT'] ?? 5672,
                    user: $_ENV['RABBITMQ_USERNAME'],
                    password: $_ENV['RABBITMQ_PASSWORD'],
                    keepalive: true
                );
                $this->channel = $this->connection->channel();
                $this->channel->basic_qos(0, 1, false);
            } catch (AMQPRuntimeException | AMQPChannelClosedException | Throwable $e) {
                echo $e->getMessage() . PHP_EOL;
                echo 'Trying to connect again in 1 second.';
                sleep(1);
            }
        }
    }

    public function prepareChannel(string $queueName, string $exchangeName, ?string $routingKey = null): void
    {
        $this->connect();
        $this->channel->queue_declare($queueName, durable: true, auto_delete: false);
        $this->channel->exchange_declare($exchangeName, AMQPExchangeType::DIRECT, durable: true, auto_delete: false);
        $this->channel->queue_bind(
            $queueName,
            $exchangeName,
            !is_null($routingKey) ? $routingKey : $queueName
        );

        $this->queueName = $queueName;
        $this->exchangeName = $exchangeName;
        $this->routingKey = !is_null($routingKey) ? $routingKey : $queueName;
    }

    public function publish(QueueMessage $message): void
    {
        if (empty($message->getOption('exchangeName'))) {
            throw new Exception('exchangeNotDeclared');
        }

        $this->connect();

        $msg = new AMQPMessage((string)$message);
        $this->channel->basic_publish(
            $msg,
            $message->getOption('exchangeName'),
            $message->getOption('routingKey')
        );

        $this->channel->close();
        //        $this->connection->close();
    }

    public function publishInBatch(array $messages): void
    {
        foreach ($messages as $message) {
            $this->publish($message);
        }
    }

    public function consume(Closure $messageReceiver): void
    {
        if (empty($this->queueName)) {
            throw new Exception('queueNotDeclared');
        }

        $this->channel->basic_consume(
            $this->queueName,
            callback: function ($message) use ($messageReceiver) {
                $this->logSystem->debug($message->getBody());
                $messageReceiver($message);
            }
        );
    }

    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }
}
