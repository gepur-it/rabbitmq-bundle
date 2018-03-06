<?php
/**
 * @author Marina Mileva <m934222258@gmail.com>
 * @since 23.11.17
 */

namespace GepurIt\RabbitMqBundle;

use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;

/**
 * Class Rabbit
 * @package RabbitMqBundle
 */
class Rabbit
{
    /** @var AMQPConnection */
    private $connection;

    /** @var AMQPChannel */
    private $channel;

    /** @var AMQPExchange[] */
    private $exchanges = [];

    public function __construct($params)
    {
        $this->connection = new AMQPConnection($params);
    }

    /**
     * @return AMQPChannel
     * @throws \AMQPConnectionException
     */
    public function getChannel()
    {
        if (null !== $this->channel && $this->channel->isConnected()) {
            return $this->channel;
        }
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }
        $this->connection->reconnect();
        $this->channel = new AMQPChannel($this->connection);

        return $this->channel;
    }

    /**
     * @param string $exchangeName
     * @return AMQPExchange //--with getting Queue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function getExchange(string $exchangeName)
    {
        if (isset($this->exchanges[$exchangeName])) {
            return $this->exchanges[$exchangeName];
        }
        $exchange = new AMQPExchange($this->getChannel());
        $exchange->setName($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $this->exchanges[$exchangeName] = $exchange;
        $queue = $this->getQueue($exchangeName);
        $queue->bind($exchangeName, $exchangeName);

        return $exchange;
    }

    /**
     * @param string $queueName
     * @return AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function getQueue(string $queueName)
    {
        $queue = new AMQPQueue($this->getChannel());
        $queue->setName($queueName);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        return $queue;
    }
}