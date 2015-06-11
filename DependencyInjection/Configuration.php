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
        $adminServices = ['sonata','none'];

        $rootNode
            ->children()
                ->scalarNode('manager_registry')->defaultNull()->end()
                ->append($this->dbDriver())
                ->arrayNode('allowed_locales')
                    ->requiresAtLeastOneElement()->defaultValue(['en'])->prototype('scalar')->end()
                ->end()
                ->arrayNode('translator_extensions')
                    ->defaultValue([])->prototype('scalar')->end()
                ->end()
                ->enumNode('admin_service')->values($adminServices)->defaultValue('none')->end()
                ->arrayNode('translation_resources')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->isRequired()->end()
                            ->scalarNode('class')->isRequired()->end()
                            ->scalarNode('service')->cannotBeEmpty()->defaultValue('f_devs_locale.model_manager')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function dbDriver()
    {
        $supportedDrivers = ['mongodb'];
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('db');

        $rootNode
            ->addDefaultsIfNotSet()
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
