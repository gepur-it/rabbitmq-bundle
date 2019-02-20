<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

use GepurIt\RabbitMqBundle\RabbitInterface;

/**
 * Class ConfiguratorInterface
 * @package GepurIt\RabbitMqBundle\Configurator
 */
interface ConfiguratorInterface
{
    /**
     * @return \AMQPQueue
     * @internal
     */
    public function getQueue(): \AMQPQueue;

    /**
     * @return \AMQPExchange
     * @internal
     */
    public function getExchange(): \AMQPExchange;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string      $message
     *
     * @param null|string $routingKey
     *
     * @return bool
     */
    public function publish(string $message, ?string $routingKey = null): bool;

    /**
     * @param callable $callback
     */
    public function consume(callable $callback);

    /**
     * @return int
     */
    public function getTtl():int;

    /**
     * @return RabbitInterface
     */
    public function getRabbit(): RabbitInterface;

    /**
     * @param string      $message
     * @param null|string $routingKey
     *
     * @return void
     */
    public function push(string $message, ?string $routingKey = null): void;
}
