<?php

namespace FDevs\Bundle\LocaleBundle;

use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use FDevs\Bridge\Locale\DependencyInjection\Compiler\FormPass;
use FDevs\Bridge\Locale\DependencyInjection\Compiler\JmsSerializerPass;
use FDevs\Bridge\Locale\DependencyInjection\Compiler\TranslatorPass;
use FDevs\Bridge\Locale\DependencyInjection\FDevsLocaleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FDevsLocaleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addRegisterMappingsPass($container);
        $container->addCompilerPass(new JmsSerializerPass());
        $container->addCompilerPass(new FormPass());
        $container->addCompilerPass(new TranslatorPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function createContainerExtension()
    {
        return new FDevsLocaleExtension();
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $refl = new \ReflectionClass('FDevs\Bridge\Locale\FDevsLocale');

        $mappings = [realpath(dirname($refl->getFileName()).'/Resources/config/doctrine') => 'FDevs\Locale\Model'];

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
