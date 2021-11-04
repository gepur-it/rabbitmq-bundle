<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */
declare(strict_types=1);

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
     * @return \AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     * @internal use consume() instead
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
     * @return \AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     * @internal use publish() instead
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

        $deferred = $this->getDeferred();
        if (null !== $deferred) {
            $deferredExchange = new \AMQPExchange($channel);
            /** @var string $deferred */
            $deferredExchange->setName($deferred);
            $deferredExchange->setType(AMQP_EX_TYPE_FANOUT);
            $deferredExchange->declareExchange();
            $deferredQueue = new \AMQPQueue($channel);
            $deferredQueue->setName($deferred);
            $deferredQueue->setArgument('x-dead-letter-exchange', $this->getName());
            $deferredQueue->setArgument('x-dead-letter-routing-key', $this->getName());
            $deferredQueue->setArgument('x-message-ttl', $this->getTtl());
            $deferredQueue->declareQueue();
            $deferredQueue->bind($deferred, $this->getName());

            $queue->setArgument('x-dead-letter-exchange', $this->getDeferred());
            $queue->setArgument('x-dead-letter-routing-key', $this->getDeferred());
        }

        $queue->declareQueue();
        $queue->bind($this->getName(), $this->getName());

        return $exchange;
    }

    /**
     * @param string      $message
     * @param null|string $routingKey
     * @param null|string $flags
     * @param array $attributes
     *
     * @return bool
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function publish(string $message, ?string $routingKey = null, int $flags = AMQP_NOPARAM, array $attributes = []): bool
    {
        $routingKey = $routingKey ?? $this->getName();

        return $this->getExchange()->publish($message, $routingKey, $flags, $attributes);
    }

    /**
     * @param callable $callback
     *
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPEnvelopeException
     * @throws \AMQPQueueException
     */
    public function consume(callable $callback): void
    {
        $this->getQueue()->consume($callback);
    }

    /**
     * @param string      $message
     * @param string|null $routingKey
     * @param int         $flags
     * @param array       $attributes
     */
    public function push(string $message, ?string $routingKey = null, int $flags = AMQP_NOPARAM, array $attributes = ['delivery_mode' => 2]): void
    {
        $this->getRabbit()->persist($this, $message, $routingKey, $flags, $attributes);
    }
}
