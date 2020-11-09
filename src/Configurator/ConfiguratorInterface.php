<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */
declare(strict_types=1);

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
     * @param string|null $routingKey
     * @param int         $flags
     * @param array       $attributes
     *
     * @return bool
     */
    public function publish(string $message, ?string $routingKey = null, int $flags = AMQP_NOPARAM, array $attributes = []): bool;

    /**
     * @param callable $callback
     */
    public function consume(callable $callback): void;

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
