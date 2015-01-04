<?php

namespace FDevs\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_locale');

        $rootNode
            ->children()
                ->append($this->dbDriver())
            ->end();

        return $treeBuilder;
    }

    private function dbDriver()
    {
        $supportedDrivers = ['mongodb'];
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('db');

        $rootNode
            ->children()
                ->scalarNode('driver')
                    ->defaultValue('mongodb')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                    ->end()
                ->end()
                ->scalarNode('manager_name')->defaultNull()->end()
            ->end()
        ;

        return $rootNode;
    }

}
