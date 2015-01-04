<?php

namespace FDevs\LocaleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FDevsLocaleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($config['db'])) {
            $container->setParameter($this->getAlias() . '.model_manager_name', $config['db']['manager_name']);
            $container->setParameter($this->getAlias() . '.backend_type_' . $config['db']['driver'], true);
            $loader->load($config['db']['driver'] . '.xml');
        }

        $loader->load('services.xml');
    }
}
