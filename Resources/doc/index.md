Getting Started With Locale Bundle
==================================

## Installation and usage

Installation and usage is a quick:

1. Download LocaleBundle using composer
2. Enable the bundle
3. Use the bundle

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
        new FDevs\Bundle\LocaleBundle\FDevsLocaleBundle(),
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
use FDevs\Bridge\Locale\Form\Type\LocaleText\TransTextType;
use FDevs\Bridge\Locale\Form\Type\LocaleText\TransTextareaType;

class UserType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TransTextType::class)
            ->add('lastName', TransTextType::class)
            ->add('about', TransTextareaType::class);
     }
}
```

#### translate you text in twig

```twig
{{ user.about|t }}
{{ user.firstName|t }}
{{ user.lastName|t }}
```
