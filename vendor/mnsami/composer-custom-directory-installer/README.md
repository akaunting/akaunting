composer-custom-directory-installer
===================================

A composer plugin, to install differenty types of composer packages in custom directories outside the default composer default installation path which is in the `vendor` folder.

This is not another `composer-installer` library for supporting non-composer package types i.e. `application` .. etc. This is only to add the flexibility of installing composer packages outside the vendor folder. This package only supports `composer` package types,

https://getcomposer.org/doc/04-schema.md#type

> The type of the package. It defaults to library.
>
> Package types are used for custom installation logic. If you have a package that needs some special logic, you can define a custom type. This could be a symfony-bundle, a wordpress-plugin or a typo3-module. These types will all be specific to certain projects, and they will need to provide an installer capable of installing packages of that type.

How to use
----------

- Include the composer plugin into your `composer.json` `require` section::

```
  "require":{
    "php": ">=5.3",
    "mnsami/composer-custom-directory-installer": "1.1.*",
    "monolog/monolog": "*"
  }
```

- In the `extra` section define the custom directory you want to the package to be installed in::

```
  "extra":{
    "installer-paths":{
      "./monolog/": ["monolog/monolog"]
      }
    }
```

 by adding the `installer-paths` part, you are telling composer to install the `monolog` package inside the `monolog` folder in your root directory.

- As an added new feature, we have added more flexibility in defining your download directory same like the `composer/installers`, in other words you can use variables like `{$vendor}` and `{$name}` in your `installer-path` section::

```
  "extra": {
    "installer-paths": {
      "./customlibs/{$vendor}/db/{$name}": ["doctrine/orm"]
    }
  }
```

the above will manage to install the `doctrine/orm` package in the root folder of your project, under `customlibs`.

Note
----

Composer `type: project` is not supported in this installer, as packages with type `project` only make sense to be used with application shells like `symfony/framework-standard-edition`, to be required by another package.
