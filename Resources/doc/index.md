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
        new FDevs\LocaleBundle\FDevsLocaleBundle(),
    );
}
```

### Step 3: Use the bundle

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
