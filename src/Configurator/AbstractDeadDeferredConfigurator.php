<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

/**
 * Class BaseConfigurator
 * @package GepurIt\RabbitMqBundle
 */
abstract class AbstractDeadDeferredConfigurator implements ConfiguratorInterface
{
    /**
     * @return string
     */
    abstract public function getDeferred(): ?string;

    /**
     * @internal use consume() instead
     * @return \AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function getQueue(): \AMQPQueue
    {
        $queue = new \AMQPQueue($this->getRabbit()->getChannel());
        $queue->setName($this->getName());
        $queue->setFlags(AMQP_DURABLE);

        if (null !== $this->getDeferred()) {
            $queue->setArgument('x-dead-letter-exchange', $this->getDeferred());
            $queue->setArgument('x-dead-letter-routing-key', $this->getDeferred());
        }
        $queue->declareQueue();

        return $queue;
    }

    /**
     * @internal use publish() instead
     * @return \AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function getExchange(): \AMQPExchange
    {
        $channel = $this->getRabbit()->getChannel();

        $exchange = new \AMQPExchange($channel);
        $exchange->setName($this->getName());
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new \AMQPQueue($channel);
        $queue->setName($this->getName());
        $queue->setFlags(AMQP_DURABLE);

        if (null !== $this->getDeferred()) {
            $deferredExchange = new \AMQPExchange($channel);
            $deferredExchange->setName($this->getDeferred());
            $deferredExchange->setType(AMQP_EX_TYPE_FANOUT);
            $deferredExchange->declareExchange();
            $deferredQueue = new \AMQPQueue($channel);
            $deferredQueue->setName($this->getDeferred());
            $deferredQueue->setArgument('x-dead-letter-exchange', $this->getName());
            $deferredQueue->setArgument('x-dead-letter-routing-key', $this->getName());
            $deferredQueue->setArgument('x-message-ttl', $this->getTtl());
            $deferredQueue->declareQueue();
            $deferredQueue->bind($this->getDeferred(), $this->getName());

            $queue->setArgument('x-dead-letter-exchange', $this->getDeferred());
            $queue->setArgument('x-dead-letter-routing-key', $this->getDeferred());
        }

        $queue->declareQueue();
        $queue->bind($this->getName(), $this->getName());

        return $exchange;
    }

    /**
     * @param string      $message
     *
     * @param null|string $routingKey
     *
     * @return bool
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function publish(string $message, ?string $routingKey = null): bool
    {
        $routingKey = $routingKey??$this->getName();
        return $this->getExchange()->publish($message, $routingKey);
    }

    /**
     * @param callable $callback
     *
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function consume(callable $callback)
    {
        $this->getQueue()->consume($callback);
    }

    /**
     * @param string      $message
     * @param null|string $routingKey
     *
     * @return void
     */
    public function push(string $message, ?string $routingKey = null): void
    {
        $this->getRabbit()->persist($this, $message, $routingKey);
    }
}
