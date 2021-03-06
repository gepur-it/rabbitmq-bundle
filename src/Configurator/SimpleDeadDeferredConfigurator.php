<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */
declare(strict_types=1);

namespace GepurIt\RabbitMqBundle\Configurator;

use GepurIt\RabbitMqBundle\RabbitInterface;

/**
 * Class SimpleDeadDeferredConfigurator
 * @package GepurIt\RabbitMqBundle\Configurator
 */
class SimpleDeadDeferredConfigurator extends AbstractDeadDeferredConfigurator implements ConfiguratorInterface
{
    private int $ttl;
    private RabbitInterface $rabbit;
    private string $name;
    private ?string $deferred = null;

    /**
     * SimpleDeadDeferredConfigurator constructor.
     *
     * @param RabbitInterface $rabbit
     * @param string          $name
     * @param string|null     $deferred
     * @param int|null        $ttl
     */
    public function __construct(RabbitInterface $rabbit, string $name, ?string $deferred = null, ?int $ttl = 600000)
    {
        $this->rabbit   = $rabbit;
        $this->name     = $name;
        $this->deferred = $deferred;
        $this->ttl      = (null === $ttl) ? 600000 : $ttl;
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
