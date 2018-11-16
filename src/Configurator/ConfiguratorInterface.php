<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

/**
 * Class ConfiguratorInterface
 * @package GepurIt\RabbitMqBundle\Configurator
 */
interface ConfiguratorInterface
{
    /**
     * @return \AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function getQueue(): \AMQPQueue;

    /**
     * @return \AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function getExchange(): \AMQPExchange;

    /**
     * @return string
     */
    public function getName(): string;

        /**
     * @param string $message
     *
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function publish(string $message);

    /**
     * @param callable $callback
     *
     * @return mixed
     */
    public function consume(callable $callback);

    /**
     * @return int
     */
    public function getTtl():int;
}
