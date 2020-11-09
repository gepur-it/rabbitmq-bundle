<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 19.02.19
 */
declare(strict_types=1);

namespace GepurIt\RabbitMqBundle;

use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

/**
 * Class Cocainum
 * @package GepurIt\RabbitMqBundle
 */
class Cocainum
{
    private ConfiguratorInterface $configurator;
    private string $message;
    private ?string $routingKey = null;
    private int $flags;
    private array $attributes;

    /**
     * Cocainum constructor.
     *
     * @param ConfiguratorInterface $configurator
     * @param string                $message
     * @param string|null           $routingKey
     * @param int                   $flags
     * @param array                 $attributes
     */
    public function __construct(ConfiguratorInterface $configurator,  string $message, ?string $routingKey, int $flags = AMQP_NOPARAM, array $attributes = [])
    {
        $this->configurator = $configurator;
        $this->message = $message;
        $this->routingKey = $routingKey;
        $this->flags = $flags;
        $this->attributes = $attributes;
    }

    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return $this->configurator;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return null|string
     */
    public function getRoutingKey(): ?string
    {
        return $this->routingKey;
    }

    /**
     * @return int
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
