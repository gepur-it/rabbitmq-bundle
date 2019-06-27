<?php
/**
 * @author Marina Mileva <m934222258@gmail.com>
 * @since 23.11.17
 */

namespace GepurIt\RabbitMqBundle;

use AMQPConnection;
use AMQPChannel;
use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

/**
 * Class Rabbit
 * @package RabbitMqBundle
 */
class Rabbit implements RabbitInterface
{
    /** @var AMQPConnection */
    private $connection;

    /** @var AMQPChannel|null */
    private $channel;

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
    public function getChannel(): AMQPChannel
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
     * @param ConfiguratorInterface $configurator
     * @param string                $message
     * @param string|null           $routingKey
     */
    public function persist(ConfiguratorInterface $configurator, string $message, ?string $routingKey = null): void
    {
        $this->cocainums[] = new Cocainum($configurator, $message, $routingKey);
    }

    /**
     * Flush all persist messages
     */
    public function flush(): void
    {
        while ($cocainum = array_shift($this->cocainums)) {
            $cocainum->getConfigurator()->publish($cocainum->getMessage(), $cocainum->getRoutingKey());
        }
    }

    public function isClean(): bool
    {
        return count($this->cocainums) === 0;
    }
}
