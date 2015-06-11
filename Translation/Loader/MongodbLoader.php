<?php

namespace FDevs\LocaleBundle\Translation\Loader;

use FDevs\Locale\Translation\Loader\MongodbLoader as BaseMongodbLoader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\MessageCatalogue;

class MongodbLoader extends BaseMongodbLoader implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function load($resource, $locale, $domain = 'messages')
    {
        try {
            /** @var \Doctrine\MongoDB\Collection $collection */
            $collection = $this->container->get($resource)->getDocumentCollection($domain);
            $name = $collection->getName();
            $mongoDB = $collection->getDatabase()->getMongoDB();
        } catch (\Exception $e) {
            throw new InvalidResourceException($e->getMessage(), $e->getCode(), $e);
        }
        $messages = $this->getMessages($mongoDB, $locale, $name);
        $catalogue = new MessageCatalogue($locale);
        $name = str_replace('\\', '', $domain);
        $catalogue->add($messages, $name);

        return $catalogue;
    }
}
