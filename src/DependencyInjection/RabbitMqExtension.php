<?php
declare(strict_types=1);

namespace GepurIt\RabbitMqBundle\DependencyInjection;

use GepurIt\RabbitMqBundle\Rabbit;
use GepurIt\RabbitMqBundle\RabbitInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;

/**
 * Class LdapExtension
 * @package RabbitMqBundle\DependencyInjection
 * @codeCoverageIgnore
 */
class RabbitMqExtension extends BaseExtension
{
    /**
     * Loads a specific configuration.
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        if (null === $configuration) {
            return;
        }
        $config = $this->processConfiguration($configuration, $configs);
        $defaultConnection = $config['default_connection'];
        if (!array_key_exists($defaultConnection, $config['connections'])) {
            $message = "RabbitMQ default connection '{$defaultConnection}' is not defined";
            throw new InvalidConfigurationException($message);
        }
        foreach ($config['connections'] as $name => $params) {
            $this->loadRabbit($name, $params, $container);
        }
        $container->addAliases(
            [
                'rabbit_mq'   => 'rabbit_mq.'.$defaultConnection,
                RabbitInterface::class => 'rabbit_mq.'.$defaultConnection,
            ]
        );
    }

    /**
     * @param $name
     * @param array $params
     * @param ContainerBuilder $container
     */
    private function loadRabbit($name, array $params, ContainerBuilder $container): void
    {
        $definition = new Definition();
        $definition->setClass(Rabbit::class);
        $definition->setArguments([$params]);
        $container->setDefinition('rabbit_mq.'.$name, $definition);
    }
}
