<?php

namespace FDevs\LocaleBundle\Twig;

use FDevs\Locale\Twig\TranslatorExtension as BaseExtension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TranslatorExtension extends BaseExtension implements ContainerAwareInterface
{
    /** @var ContainerInterface|null */
    private $container = null;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * get Default Locale
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        $locale = parent::getDefaultLocale();
        if ($this->container && $this->container->isScopeActive('request') && $this->container->has('request')) {
            $locale = $this->container->get('request')->getLocale();
        }

        return $locale;
    }

}
