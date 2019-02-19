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
use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

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

    /** @var Cocainum[]  */
    private $cocainums = [];

    /**
     * Rabbit constructor.
     *
     * @param $params
     */
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

    /**
     * @param ConfiguratorInterface $configurator
     * @param string                $message
     * @param null|string           $routingKey
     */
    public function persist(ConfiguratorInterface $configurator, string $message, ?string $routingKey = null)
    {
        $this->cocainums[] = new Cocainum($configurator, $message, $routingKey);
    }

    /**
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function flush()
    {
        while ($cocainum = array_shift($this->cocainums)) {
            $cocainum->getConfigurator()->publish($cocainum->getMessage(), $cocainum->getRoutingKey());
        }
    }
}
