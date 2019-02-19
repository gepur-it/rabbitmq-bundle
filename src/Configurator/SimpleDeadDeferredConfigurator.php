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
        $this->ttl = $ttl;

        parent::__construct($rabbit, $name, $deferred);
    }

    /**
     * @return string
     */
    public function getDeferred(): string
    {
        if (null === parent::getDeferred()) {
           return $this->getName().'_deferred';
        }

        return parent::getDeferred();
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }
}
