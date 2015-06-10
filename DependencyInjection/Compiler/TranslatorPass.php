<?php

namespace FDevs\LocaleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TranslatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('translator.default') || !$container->hasParameter('f_devs_locale.translation_resources')) {
            return;
        }
        $definition = $container->getDefinition('translator.default');
        $resources = $container->getParameter('f_devs_locale.translation_resources');
        $locales = $container->getParameter('f_devs_locale.allowed_locales');
        foreach ($resources as $res) {
            foreach ($locales as $locale) {
                $definition->addMethodCall('addResource', [$res['type'], $res['service'], $locale, $res['class']]);
            }
        }
    }
}
