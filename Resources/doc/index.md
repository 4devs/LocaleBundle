Getting Started With Locale Bundle
==================================

## Installation and usage

Installation and usage is a quick:

1. Download LocaleBundle using composer
2. Enable the bundle
3. Use the bundle
4. Redirect to locale route
5. Use with Symfony [Translation](https://github.com/symfony/Translation)

### Step 1: Download Locale bundle using composer

Add Locale bundle in your composer.json:

```json
{
    "require": {
        "fdevs/locale-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update fdevs/locale-bundle
```

Composer will install the bundle to your project's `vendor/fdevs` directory.


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FDevs\LocaleBundle\FDevsLocaleBundle(),
    );
}
```

### Step 3: Use the bundle

#### add translatable field to model

##### php model
```php
<?php
namespace UserBundle\Model;

use FDevs\ContactList\Model\Connect;
use FDevs\Locale\Model\LocaleText;
use FOS\UserBundle\Model\User as BaseUser;
use FDevs\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User extends BaseUser
{
    /** @var Collection|array|LocaleText[] */
    protected $firstName;
    
    /** @var Collection|array|LocaleText[] */
    protected $lastName;

    /** @var Collection|array|LocaleText[] */
    protected $about;
    
    //.....
}
```
##### mongodb

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mongo-mapping xmlns="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping"
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xsi:schemaLocation="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping
                        http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping.xsd">

    <document name="UserBundle\Model\User" collection="team">
    
        <embed-many target-document="FDevs\Locale\Model\LocaleText" field="lastName"/>
        <embed-many target-document="FDevs\Locale\Model\LocaleText" field="firstName"/>
        <embed-many target-document="FDevs\Locale\Model\LocaleText" field="about"/>
        
    </document>

</doctrine-mongo-mapping>
```

#### use in form

```php
<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'trans_text')
            ->add('lastName', 'trans_text')
            ->add('about', 'trans_textarea');
     }
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'fdevs_user';
    }
}
```

#### translate you text in twig

```twig
{{ user.about|t }}
{{ user.firstName|t }}
{{ user.lastName|t }}
```

### Step 4: Redirect to locale route

add you route

```yml
#app/config/routing.yml
home_redirect:
    pattern: /
    defaults:
        _controller: FDevs:LocaleBundle:Locale:switch
        route: f_devs_core_homepage
        statusCode: 301
        useReferrer: false

```

### Step 5: Use with Symfony Translation

#### add configure loader

```yml
#app/config/routing.yml
f_devs_locale:
    allowed_locales: %allowed.locales%
    admin_service: 'sonata'
    translation_resources:
          - {type: 'mongodb', class: 'FDevs\Locale\Model\Translation'}
          
sonata_admin:
    dashboard:
        groups:
            label.locale:
                label_catalogue: FDevsLocaleBundle
                items:
                    - f_devs_locale.admin.translation
```

#### in twig templates

```twig
{{ 'symfony'|trans({},'FDevsLocaleModelTranslation') }}
```

do not forget to add a key `symfony` database
