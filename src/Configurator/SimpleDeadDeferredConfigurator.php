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
    /** @var int  */
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
     * @param string      $name
     * @param RabbitInterface      $rabbit
     * @param int         $ttl
     * @param null|string $deferred
     */
    public function __construct(RabbitInterface $rabbit, int $ttl, string $name, ?string $deferred)
    {
        $this->rabbit = $rabbit;
        $this->ttl = $ttl;
        $this->name = $name;
        $this->deferred = $deferred;
    }

    /**
     * @return string
     */
    public function getDeferred(): string
    {
        if (null === $this->deferred) {
            $this->deferred = $this->getName().'_deferred';
        }

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
