<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 19.02.19
 */

namespace GepurIt\RabbitMqBundle;

use AMQPChannel;
use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

/**
 * Class RabbitInterface
 * @package GepurIt\RabbitMqBundle
 */
interface RabbitInterface
{
    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel;

    /**
     * @param ConfiguratorInterface $configurator
     * @param string                $message
     * @param null|string           $routingKey
     */
    public function persist(ConfiguratorInterface $configurator, string $message, ?string $routingKey = null): void;

    /**
     * Flush all persist messages
     */
    public function flush(): void;

    /**
     * @return bool
     */
    public function isClean(): bool;
}
