<?php

namespace FDevs\LocaleBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SerializerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('jms_serializer.metadata.file_locator')) {
            return;
        }
        $def = $container->getDefinition('jms_serializer.metadata.file_locator');
        $arg = $def->getArgument(0);
        if (isset($arg['FDevs\LocaleBundle'])) {
            unset($arg['FDevs\LocaleBundle']);
        }
        if (!isset($arg['FDevs\Locale'])) {
            $refl = new \ReflectionClass('FDevs\Locale\LocaleTextInterface');
            $arg['FDevs\Locale'] = dirname($refl->getFileName()) . '/Resources/config/serializer';
            $def->replaceArgument(0, $arg);
        }
    }

}