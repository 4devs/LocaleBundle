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

        $refl = new \ReflectionClass('FDevs\Locale\LocaleTextInterface');
        $loader = new Loader\XmlFileLoader($container, new FileLocator([__DIR__ . '/../Resources/config', realpath(dirname($refl->getFileName()) . '/Resources/config')]));

        $container->setParameter($this->getAlias() . '.allowed_locales', $config['allowed_locales']);

        if (isset($config['db'])) {
            $container->setParameter($this->getAlias() . '.model_manager_name', $config['db']['manager_name']);
            $container->setParameter($this->getAlias() . '.backend_type_' . $config['db']['driver'], true);
            $loader->load($config['db']['driver'] . '.xml');
        }
        if ($config['manager_registry']) {
            $container->setAlias($this->getAlias() . '.manager_registry', $config['manager_registry']);
            $loader->load('validator.xml');
        }

        $loader->load('twig_extensions.xml');

        if (count($config['translator_extensions'])) {
            $trans = $container->getDefinition('f_devs_locale.twig_extension');
            $trans->replaceArgument(0, $config['translator_extensions']);
        }

        $loader->load('form.xml');
    }
}
