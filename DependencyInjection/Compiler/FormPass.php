<?php

namespace FDevs\LocaleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('form.extension')) {
            $container->getDefinition('twig.extension.form')->addTag('twig.extension');
            $reflClass = new \ReflectionClass('FDevs\Locale\LocaleTextInterface');
            $container->getDefinition('twig.loader.filesystem')->addMethodCall('addPath', [dirname($reflClass->getFileName()).'/Resources/views/Form']);
        }

        if ($container->hasParameter('twig.form.resources')) {
            $template = "fdevs_locale_fields.html.twig";
            $resources = $container->getParameter('twig.form.resources');
            if (!in_array($template, $resources)) {
                $resources[] = $template;
                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
