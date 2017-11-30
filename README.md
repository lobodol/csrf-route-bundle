# GenedysCsrfRouteBundle

This [Symfony3](http://symfony.com) bundle provides route annotation and options to secure routes against [CSRF attacks](http://en.wikipedia.org/wiki/Cross-site_request_forgery) and without using forms.

[![Latest Stable Version](https://poser.pugx.org/genedys/csrf-route-bundle/v/stable)](https://packagist.org/packages/genedys/csrf-route-bundle) [![Total Downloads](https://poser.pugx.org/genedys/csrf-route-bundle/downloads)](https://packagist.org/packages/genedys/csrf-route-bundle) [![Latest Unstable Version](https://poser.pugx.org/genedys/csrf-route-bundle/v/unstable)](https://packagist.org/packages/genedys/csrf-route-bundle) [![License](https://poser.pugx.org/genedys/csrf-route-bundle/license)](https://packagist.org/packages/genedys/csrf-route-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/981a1365-6411-4c50-86bf-6637cbba595c/mini.png)](https://insight.sensiolabs.com/projects/981a1365-6411-4c50-86bf-6637cbba595c)


## Installation

Use [Composer](http://getcomposer.org) to install the bundle:

`composer require genedys/csrf-route-bundle`

or add the following line in your `composer.json` file:

```json
    "require": {
        ...
        "genedys/csrf-route-bundle": "^3.0",
        ...
    }
```

Then, register the bundle in your application's bundles.php file:

```php
    // bundles.php
    return [
        // ...
        Genedys\CsrfRouteBundle\GenedysCsrfRouteBundle => ['all' => true],
        // ...
    ];
```


## Configuration

Configuration reference :

```yaml
genedys_csrf_route:
    enabled: true
    field_name: _token
```

 - **enabled** : Enable or disable the token verification (default: `true`);
 - **field_name** : The name of the field appended to route URLs (default: `_token`).


## Usage

The only thing to do to use this package is to add some configurations to the routes you want to protect.

The bundle adds a router which can append a token query parameter on route generation and
a controller listener validate which validates token on called routes.

### Options configuration

The bundle checks controller calls and search for a `csrf_token` option. The available parameters for this options are:
 - `token` : The token parameter name (by default `_token`)
 - `intention` : The token intention. Different intentions generate different tokens (by default `null` which results to the route name).
 - `methods` : The HTTP method(s) when the CSRF token is validated (by default `GET`).

```yaml
# app/config/routing.yml
homepage:
    ...
    options:
        - csrf_token:
            - token: '_token'
            - intention: null
            - methods: [GET]
```

You can also only specify the `csrf_token` option to `true` to use default parameters.

```yaml
# app/config/routing.yml
homepage:
    ...
    options: { csrf_token: true }
```


### Annotation configuration

If you use annotations to configurate your routes, then the easiest way it to add
an additionnal annotation to the sensible actions:

```php
<?php
// src Acme\DemoBundle\Controller\DefaultController.php

// ...
use Genedys\CsrfRouteBundle\Annotation\CsrfToken;
// ...

class DefaultController {
    // ...
    /**
     * ...
     * @CsrfToken
     */
    public function sensibleAction()
    {
        //...
    }
    // ...
}
```


### Twig integration

As the bundle provides a custom router, CSRF tokens are automatically appended to url generated with `path(...)` and `url(...)` on Twig templates.


### Routers compatibility

This bundle overrides the default Symfony router.
In case you use other bundles which does the same thing (for instance [JMSI18nRoutingBundle](https://github.com/schmittjoh/JMSI18nRoutingBundle)),
the router integrated on this bundle works automatically as
an adapter on previously configurated router.
The only thing to take care is to register the GenedysCsrfRouterBundle after the bundle which overrides the router.


## Credits

Created by [Fabien Antoine](http://www.fantoine.com) for [Genedys](https://www.genedys.com).


## License

This bundle is under the [MIT license](LICENSE).
