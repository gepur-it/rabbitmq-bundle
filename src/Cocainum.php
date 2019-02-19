<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 19.02.19
 */

namespace GepurIt\RabbitMqBundle;

use GepurIt\RabbitMqBundle\Configurator\ConfiguratorInterface;

/**
 * Class Cocainum
 * @package GepurIt\RabbitMqBundle
 */
class Cocainum
{
    /**
     * @var ConfiguratorInterface
     */
    private $configurator;

    /**
     * @var string
     */
    private $message;

    /**
     * @var null|string
     */
    private $routingKey;

    /**
     * Cocainum constructor.
     *
     * @param ConfiguratorInterface $configurator
     * @param string                $message
     * @param null|string           $routingKey
     */
    public function __construct(ConfiguratorInterface $configurator,  string $message, ?string $routingKey)
    {
        $this->configurator = $configurator;
        $this->message = $message;
        $this->routingKey = $routingKey;
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
}
