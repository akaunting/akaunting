ZipStream PHP
=============

A fast and simple streaming zip file downloader for PHP. Using this library will
save you from having to write the Zip to disk. You can directly send it to the
user, which is much faster. It can work with S3 buckets or any PSR7 Stream.

.. toctree::

   index
   Symfony
   Options
   StreamOutput
   FlySystem
   PSR7Streams
   Nginx
   Varnish
   ContentLength

Installation
---------------

Simply add a dependency on ``maennchen/zipstream-php`` to your project's
``composer.json`` file if you use Composer to manage the dependencies of your
project. Use following command to add the package to your project's
dependencies:

.. code-block:: sh
   composer require maennchen/zipstream-php

If you want to use``addFileFromPsr7Stream```
(``Psr\Http\Message\StreamInterface``) or use a stream instead of a
``resource`` as ``outputStream``, the following dependencies must be installed
as well:

.. code-block:: sh
   composer require psr/http-message guzzlehttp/psr7

If ``composer install`` yields the following error, your installation is missing
the `mbstring extension <https://www.php.net/manual/en/book.mbstring.php>`_,
either `install it <https://www.php.net/manual/en/mbstring.installation.php>`_
or run the follwoing command:

.. code-block::
    Your requirements could not be resolved to an installable set of packages.

    Problem 1
        - Root composer.json requires PHP extension ext-mbstring * but it is
          missing from your system. Install or enable PHP's mbstrings extension.

.. code-block:: sh
   composer require symfony/polyfill-mbstring

Usage Intro
---------------

Here's a simple example:

.. code-block:: php

   // Autoload the dependencies
   require 'vendor/autoload.php';

   // create a new zipstream object
   $zip = new ZipStream\ZipStream(
      outputName: 'example.zip',

      // enable output of HTTP headers
      sendHttpHeaders: true,
   );

   // create a file named 'hello.txt'
   $zip->addFile(
      fileName: 'hello.txt',
      data: 'This is the contents of hello.txt',
   );

   // add a file named 'some_image.jpg' from a local file 'path/to/image.jpg'
   $zip->addFileFromPath(
      fileName: 'some_image.jpg',
      path: 'path/to/image.jpg',
   );

   // add a file named 'goodbye.txt' from an open stream resource
   $filePointer = tmpfile();
   fwrite($filePointer, 'The quick brown fox jumped over the lazy dog.');
   rewind($filePointer);
   $zip->addFileFromStream(
      fileName: 'goodbye.txt',
      stream: $filePointer,
   );
   fclose($filePointer);

   // add a file named 'streamfile.txt' from the body of a `guzzle` response
   // Setup with `psr/http-message` & `guzzlehttp/psr7` dependencies required.
   $zip->addFileFromPsr7Stream(
      fileName: 'streamfile.txt',
      stream: $response->getBody(),
   );

   // finish the zip stream
   $zip->finish();

You can also add comments, modify file timestamps, and customize (or
disable) the HTTP headers. It is also possible to specify the storage method
when adding files, the current default storage method is ``DEFLATE``
i.e files are stored with Compression mode 0x08.

Known Issues
---------------

The native Mac OS archive extraction tool prior to macOS 10.15 might not open
archives in some conditions. A workaround is to disable the Zip64 feature with
the option ``enableZip64: false``. This limits the archive to 4 Gb and 64k files
but will allow users on macOS 10.14 and below to open them without issue.
See `#116 <https://github.com/maennchen/ZipStream-PHP/issues/146>`_.

The linux ``unzip`` utility might not handle properly unicode characters.
It is recommended to extract with another tool like
`7-zip <https://www.7-zip.org/>`_.
See `#146 <https://github.com/maennchen/ZipStream-PHP/issues/146>`_.

It is the responsability of the client code to make sure that files are not
saved with the same path, as it is not possible for the library to figure it out
while streaming a zip.
See `#154 <https://github.com/maennchen/ZipStream-PHP/issues/154>`_.
