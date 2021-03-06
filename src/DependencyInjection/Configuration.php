<?php
declare(strict_types=1);

namespace GepurIt\RabbitMqBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package RabbitMqBundle\DependencyInjection
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rabbit_mq');


        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_connection')->defaultValue('default')->end()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('host')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('port')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('login')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('password')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('vhost')->cannotBeEmpty()->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
