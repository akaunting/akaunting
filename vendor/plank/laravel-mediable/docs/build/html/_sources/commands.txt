Artisan Commands
============================================

.. highlight:: bash

This package provides a handful of artisan commands to help keep you filesystem and database in sync.

Create a media record in the database for any files on the disk that do not already have a record. This will apply any type restrictions in the mediable configuration file.

::

    $ php artisan media:import [disk]


Delete any media records representing a file that no longer exists on the disk.

::

    $ php artisan media:prune [disk]

To perform both commands together, you can use:

::

    $ php artisan media:sync [disk]
