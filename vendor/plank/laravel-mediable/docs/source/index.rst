Plank/Laravel-Mediable
============================================

.. image:: https://img.shields.io/travis/plank/laravel-mediable/master.svg?style=flat-square
    :target: https://travis-ci.org/plank/laravel-mediable
    :alt: TravisCI
.. image:: https://img.shields.io/coveralls/plank/laravel-mediable.svg?style=flat-square
    :target: https://coveralls.io/github/plank/laravel-mediable
    :alt: Coveralls
.. image:: https://img.shields.io/sensiolabs/i/0eaf2725-64f4-4494-ae61-ca3961ba50c5.svg?style=flat-square
    :target: https://insight.sensiolabs.com/projects/0eaf2725-64f4-4494-ae61-ca3961ba50c5
    :alt: SensioLabsInsight
.. image:: https://styleci.io/repos/63791110/shield
    :target: https://styleci.io/repos/63791110
    :alt: StyleCI
.. image:: https://img.shields.io/packagist/v/plank/laravel-mediable.svg?style=flat-square
    :target: https://packagist.org/packages/plank/laravel-mediable
    :alt: Packagist

Laravel-Mediable is a package for easily uploading and attaching media files to models with Laravel 5.

Features
-------------

* Filesystem-driven approach is easily configurable to allow any number of upload directories with different accessibility.
* Many-to-many polymorphic relationships allow any number of media to be assigned to any number of other models without any need to modify the schema.
* Attach media to models with tags, to set and retrieve media for specific purposes, such as ``'thumbnail'``, ``'featured image'``, ``'gallery'`` or ``'download'``.
* Easily query media and restrict uploads by MIME type, extension and/or aggregate type (e.g. ``image`` for jpeg, png or gif).


.. toctree::
    :maxdepth: 2
    :caption: Getting Started

    installation
    configuration

.. toctree::
    :maxdepth: 2
    :caption: Guides

    uploader
    mediable
    media
    types
    commands
