<?php

namespace App\Infrastructure\Command;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractConsumerCommand
 *
 * @author Óscar Jimenez <oscar@bab-soft.com>
 * @package App\Command
 */
abstract class AbstractConsumerCommand extends Command
{
    const DEFAULT_EXCHANGE = "carnovo.topic";
    const DEFAULT_MAX_MESSAGES = 10000;

    /**
     * @var int
     */
    protected $messagesProcessed;

    /**
     * AbstractConsumerCommand constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->messagesProcessed = 0;
    }

    /**
     * @param $exchange
     * @param $queueName
     *
     * @return string
     */
    protected function buildQueueName($exchange, $queueName): string
    {
        return sprintf('%s_%s', $exchange, $queueName);
    }

    /**
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     *
     * @return AMQPStreamConnection
     */
    protected function rabbitConnection(string $host, int $port, string $user, string $password): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $password
        );
    }

    /**
     * @param AMQPStreamConnection $connection
     * @param $exchange
     * @param string $queueName
     * @param $route
     * @param $callback
     *
     * @return AMQPChannel
     */
    protected function buildChannel(
        AMQPStreamConnection $connection,
        $exchange,
        string $queueName,
        $route,
        $callback
    ): AMQPChannel {
        $channel = $connection->channel();

        $this->buildDeadLetter($channel, $exchange);

        $channel->exchange_declare(
            $exchange,
            'topic',
            false,
            true,
            false
        );

        $channel->queue_declare(
            $queueName,
            false,
            true,
            false,
            false,
            false,
            new AMQPTable([
                'x-dead-letter-exchange' => $this->buildDlxName($exchange),
                "x-queue-master-locator" => "min-masters"
            ])
        );

        $channel->queue_bind($queueName, $exchange, $route);

        // Avoid to receive more than one message if the consumer is busy
        $channel->basic_qos(
            null,
            1,
            null
        );

        $channel->basic_consume(
            $queueName,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        return $channel;
    }

    /**
     * @param OutputInterface $output
     * @param $maxMessages
     *
     * @throws \Exception
     */
    protected function handleLimitMessages(OutputInterface $output, $maxMessages): void
    {
        $this->messagesProcessed++;

        if ($this->messagesProcessed > $maxMessages) {
            throw new \Exception("Max number of messages reached. Force exit to restart from supervisor");
        } else {
            $output->writeln(
                sprintf('Message processed:'. $this->messagesProcessed . ' Limit: ' . $maxMessages),
                OutputInterface::VERBOSITY_VERBOSE
            );
        }
    }

    /**
     * @param AMQPChannel $channel
     * @param $exchange
     */
    protected function buildDeadLetter(AMQPChannel $channel, $exchange): void
    {
        $queueName = $exchange . "_dlx_queue";

        $channel->exchange_declare(
            $this->buildDlxName($exchange),
            'topic',
            false,
            true,
            false
        );

        $channel->queue_declare(
            $queueName,
            false,
            true,
            false,
            false,
            false,
            new AMQPTable([
                "x-queue-master-locator" => "min-masters"
            ])
        );

        $channel->queue_bind($queueName, $this->buildDlxName($exchange), "#");
    }

    /**
     * @param $exchange
     *
     * @return string
     */
    private function buildDlxName($exchange): string
    {
        return $exchange . "_dlx";
    }
}
