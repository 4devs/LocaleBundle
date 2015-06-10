<?php

namespace FDevs\LocaleBundle;

use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use FDevs\LocaleBundle\DependencyInjection\Compiler\FormPass;
use FDevs\LocaleBundle\DependencyInjection\Compiler\SerializerPass;
use FDevs\LocaleBundle\DependencyInjection\Compiler\TranslatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FDevsLocaleBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->addRegisterMappingsPass($container);
        $container->addCompilerPass(new SerializerPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new TranslatorPass());
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $refl = new \ReflectionClass('FDevs\Locale\LocaleTextInterface');

        $mappings = [realpath(dirname($refl->getFileName()).'/Resources/doctrine/model') => 'FDevs\Locale\Model'];

        if (class_exists('Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass')) {
            $container->addCompilerPass(
                DoctrineMongoDBMappingsPass::createXmlMappingDriver(
                    $mappings,
                    ['f_devs_locale.model_manager_name'],
                    'f_devs_locale.backend_type_mongodb'
                )
            );
        }
    }
}
