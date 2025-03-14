<?php

declare(strict_types=1);

namespace App\Shared\Domain\Adapters\MessageBroker;

use Throwable;
use DateTimeImmutable;
use PhpAmqpLib\Message\AMQPMessage;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Doctrine\DBAL\Exception\DriverException;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Exception\AMQPProtocolException;
use App\Shared\Domain\Adapters\Contracts\LogSystem;
use App\Shared\Infra\Exceptions\ValidationException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;

abstract class ConsumerBase
{
    protected string $queueName;
    protected AbstractConnection $connection;
    protected string $rawMessageBody;
    protected array $messageBody;
    protected array $metaBody;

    abstract protected function handle(): void;

    public function __construct(
        protected readonly AMQPMessage $message,
        protected readonly LogSystem $logSystem
    ) {
        $this->queueName = $message->getRoutingKey();
        $this->rawMessageBody = $message->getBody();
        $this->metaBody = json_decode($message->getBody(), true);
        $this->messageBody = $this->metaBody['data'];
        $this->connection = $message->getChannel()->getConnection();
        $this->construct();
    }

    public function construct(): void
    {
    }

    public function execute(): void
    {
        $handlerName = get_called_class();

        $this->logSystem->debug(
            json_encode(
                ['handler' => $handlerName, 'queue' => $this->queueName, 'message' => $this->messageBody],
                JSON_PRETTY_PRINT
            )
        );

        $errorHandler = function (
            Throwable $e,
            array $details = []
        ) use (
            $handlerName
        ): void {
            $data = [
                'date' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                'handler' => $handlerName,
                'type' => get_class($e),
                'message' => "{$e->getMessage()}",
                'file' => "{$e->getFile()}:{$e->getLine()}",
                'stackTrace' => $e->getTrace(),
                'queueMessage' => json_encode($this->messageBody),
                ...$details
            ];

            $jsonPayload = json_encode($data, JSON_PRETTY_PRINT);

            if ($jsonPayload !== false) {
                $this->logSystem->error($jsonPayload);
                return;
            }

            $output = sprintf(
                "[%s] %s (%s:%s)" . PHP_EOL . "%s",
                $handlerName,
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            );

            $this->logSystem->error($output);
        };

        try {
            $this->message->ack();
            $this->handle();

            $this->logSystem->info(
                json_encode(['handler' => $handlerName, 'message' => 'Message processed!'], JSON_PRETTY_PRINT)
            );

            $this->metaBody['data'] = $this->messageBody;
            $this->message->setBody(json_encode($this->metaBody));
        } catch (ValidationException $e) {
            $errorHandler($e);
            $this->message->ack();
        } catch (
            RequestException
            | ConnectException
            | AMQPRuntimeException
            | AMQPProtocolException
            | AMQPConnectionClosedException
            $e
        ) {
            $errorHandler($e);
            $this->message->nack(true);
        } catch (DriverException $e) {
            $errorHandler($e, ['query' => $e->getQuery()->getSQL(), 'values' => $e->getQuery()->getParams()]);
            $this->message->nack(true);
        } catch (Throwable $e) {
            $errorHandler($e);
            $this->message->nack(true);
        }
    }
}
