<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 19.02.19
 */

namespace GepurIt\RabbitMqBundle;

use AMQPChannel;
use AMQPExchange;
use AMQPQueue;
use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

/**
 * Class RabbitInterface
 * @package GepurIt\RabbitMqBundle
 */
interface RabbitInterface
{
    public function getChannel(): AMQPChannel;

    public function getExchange(string $exchangeName): AMQPExchange;

    public function getQueue(string $queueName): AMQPQueue;

    public function persist(ConfiguratorInterface $configurator, string $message, ?string $routingKey = null): void;

    public function flush(): void;
}
