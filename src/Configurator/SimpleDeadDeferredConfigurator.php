<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

use GepurIt\RabbitMqBundle\RabbitInterface;

/**
 * Class SimpleDeadDeferredConfigurator
 * @package GepurIt\RabbitMqBundle\Configurator
 */
class SimpleDeadDeferredConfigurator extends AbstractDeadDeferredConfigurator
{
    /** @var int */
    private $ttl;

    /** @var RabbitInterface */
    private $rabbit;

    /** @var string */
    private $name;

    /** @var null|string */
    private $deferred;

    /**
     * SimpleDeadDeferredConfigurator constructor.
     *
     * @param string          $name
     * @param RabbitInterface $rabbit
     * @param null|string     $deferred
     * @param null|int        $ttl
     */
    public function __construct(RabbitInterface $rabbit, string $name, ?string $deferred = null, ?int $ttl = null)
    {
        $this->rabbit   = $rabbit;
        $this->name     = $name;
        $this->deferred = $deferred;
        $this->ttl      = (null !== $ttl) ? $ttl : 0;
    }

    /**
     * @return string
     */
    public function getDeferred(): ?string
    {
        return $this->deferred;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return RabbitInterface
     */
    public function getRabbit(): RabbitInterface
    {
        return $this->rabbit;
    }
}
