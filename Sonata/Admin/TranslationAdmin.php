<?php

namespace FDevs\LocaleBundle\Sonata\Admin;

use FDevs\Locale\Model\Translation;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class TranslationAdmin extends Admin
{
    /**
     * {@inheritDoc}
     */
    protected $formOptions = ['cascade_validation' => true];

    /**
     * {@inheritDoc}
     */
    protected $baseRoutePattern = 'translation';

    /**
     * {@inheritDoc}
     */
    protected $translationDomain = 'FDevsLocaleBundle';

    /**
     * {@inheritDoc}
     */
    protected $baseRouteName = 'translation';

    /** @var Translator */
    private $trans;

    /** @var string */
    private $transCacheDir;

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id', 'text')
            ->add('trans', 'trans_text');

        $formMapper->getFormBuilder()
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();
                if ($data instanceof Translation && $data->getId()) {
                    $form = $event->getForm();
                    $form->add('id', 'text', ['read_only' => true]);
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id', null, ['editable' => true])
            ->add('_action', 'actions', ['actions' => ['edit' => [], 'delete' => []]]);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        $files = glob($this->transCacheDir.'/*', GLOB_MARK);
        foreach ($files as $file) {
            if (!is_dir($file)) {
                unlink($file);
            }
        }
        if ($this->trans instanceof WarmableInterface) {
            $this->trans->warmUp($this->transCacheDir);
        }
    }

    /**
     * set translator
     * 
     * @param Translator $trans
     *
     * @return self
     */
    public function setTrans(Translator $trans, $cacheDir)
    {
        $this->trans = $trans;
        $this->transCacheDir = $cacheDir;

        return $this;
    }
}
