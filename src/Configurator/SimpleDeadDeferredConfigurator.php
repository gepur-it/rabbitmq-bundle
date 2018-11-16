<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.11.18
 */

namespace GepurIt\RabbitMqBundle\Configurator;

use GepurIt\RabbitMqBundle\Rabbit;

/**
 * Class SimpleDeadDeferredConfigurator
 * @package GepurIt\RabbitMqBundle\Configurator
 */
class SimpleDeadDeferredConfigurator extends AbstractDeadDeferredConfigurator
{
    /** @var string  */
    private $name;

    /** @var Rabbit  */
    private $rabbit;

    /** @var int  */
    private $ttl;

    /** @var string */
    private $deferred;

    /**
     * SimpleDeadDeferredConfigurator constructor.
     *
     * @param string      $name
     * @param Rabbit      $rabbit
     * @param int         $ttl
     * @param null|string $deferred
     */
    public function __construct(Rabbit $rabbit, int $ttl, string $name, ?string $deferred)
    {
        $this->name = $name;
        $this->rabbit = $rabbit;
        $this->ttl = $ttl;
        $this->deferred = $deferred;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDeferred(): string
    {
        if (null === $this->deferred) {
           $this->deferred = $this->name.'_deferred';
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
     * @return Rabbit
     */
    public function getRabbit(): Rabbit
    {
        return $this->rabbit;
    }
}
