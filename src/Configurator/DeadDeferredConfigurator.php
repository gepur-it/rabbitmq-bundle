<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

use GepurIt\RabbitMqBundle\Rabbit;

/**
 * Class BaseConfigurator
 * @package GepurIt\RabbitMqBundle
 */
abstract class DeadDeferredConfigurator implements ConfiguratorInterface
{
    /** @var Rabbit  */
    private $rabbit;

    /**
     * Helper constructor.
     *
     * @param Rabbit  $rabbit
     */
    public function __construct(Rabbit $rabbit)
    {
        $this->rabbit = $rabbit;
    }

    /**
     * @return \AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function getQueue(): \AMQPQueue
    {
        $queue = new \AMQPQueue($this->rabbit->getChannel());
        $queue->setName($this->getName());
        $queue->setFlags(AMQP_DURABLE);
        $queue->setArgument('x-dead-letter-exchange', $this->getDeferred());
        $queue->setArgument('x-dead-letter-routing-key', $this->getDeferred());
        $queue->declareQueue();

        return $queue;
    }

    /**
     * @return \AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function getExchange(): \AMQPExchange
    {
        $channel = $this->rabbit->getChannel();

        $deferredExchange = new \AMQPExchange($channel);
        $deferredExchange->setName($this->getDeferred());
        $deferredExchange->setType(AMQP_EX_TYPE_FANOUT);
        $deferredExchange->declareExchange();
        $deferredQueue = new \AMQPQueue($channel);
        $deferredQueue->setName($this->getDeferred());
        $deferredQueue->setArgument('x-dead-letter-exchange', $this->getName());
        $deferredQueue->setArgument('x-message-ttl', $this->getTtl());
        $deferredQueue->declareQueue();
        $deferredQueue->bind($this->getDeferred(), $this->getName());

        $exchange = new \AMQPExchange($channel);
        $exchange->setName($this->getName());
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new \AMQPQueue($channel);
        $queue->setName($this->getName());
        $queue->setFlags(AMQP_DURABLE);
        $queue->setArgument('x-dead-letter-exchange', $this->getDeferred());
        $queue->declareQueue();
        $queue->bind($this->getName(), $this->getName());

        return $exchange;
    }

    /**
     * @return string
     */
    abstract  public function getName(): string;

    /**
     * @return string
     */
    abstract public function getDeferred(): string;


    /**
     * @param string $message
     *
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function publish(string $message)
    {
        $this->getExchange()->publish($message, $this->getName());
    }
}
