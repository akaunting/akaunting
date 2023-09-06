Uploading Files
============================================

.. highlight:: php

The easiest way to upload media to your server is with the ``MediaUploader`` class, which handles validating the file, moving it to its destination and creating a ``Media`` record to reference it. You can get an instance of the MediaUploader using the Facade and configure it with a fluent interface.

To upload a file to the root of the default disk (set in ``config/mediable.php``), all you need to do is the following:

::

    <?php
    use MediaUploader; //use the facade
    $media = MediaUploader::fromSource($request->file('thumbnail'))->upload();

Source Files
----------------------

The ``fromSource()`` method will accept any of the following:

- an instance of ``Symfony\Component\HttpFoundation\UploadedFile``, which is returned by ``$request->file()``.
- an instance of ``Symfony\Component\HttpFoundation\File``.
- an instance of ``Psr\Http\Message\StreamInterface``, which is returned by libraries using PSR-7 HTTP message interfaces, like Guzzle.
- a stream resource handle.
- a URL as a string, beginning with ``http://`` or ``https://``.
- an absolute path as a string, beginning with ``/``.

Specifying Destination
----------------------

By default, the uploader will place the file in the root of the default disk specified in ``config/mediable.php``. You can customize where the uploader will put the file on your server before you invoke the ``upload()`` method.

::

    <?php
    $uploader = MediaUploader::fromSource($request->file('thumbnail'))

    // specify a disk to use instead of the default
    ->toDisk('s3');

    // place the file in a directory relative to the disk root
    ->toDirectory('user/john/profile')

    // alternatively, specify both the disk and directory at once
    ->toDestination('s3', 'user/john/profile')

    ->upload();

Specifying Filename
--------------------

By default, the uploader will copy the source file while maintaining its original filename. You can override this behaviour by providing a custom filename.

::

    <?php
    MediaUploader::fromSource(...)
        ->useFilename('profile')
        ->upload();

You can also tell the uploader to generate a filename based on the MD5 hash of the file's contents.

::

    <?php
    MediaUploader::fromSource(...)
        ->useHashForFilename()
        ->upload();

You can restore the default behaviour with ``useOriginalFilename()``.

Handling Duplicates
----------------------

Occasionally, a file with a matching name might already exist at the destination you would like to upload to. The uploader allows you to configure how it should respond to this scenario. There are three possible behaviours:

::

    <?php

    // keep both, append incrementing counter to new file name
    $uploader->onDuplicateIncrement();

    // replace old file with new one, update existing Media record, maintain associations
    $uploader->onDuplicateUpdate();

    // replace old file and media record with new ones, break associations
    $uploader->onDuplicateReplace();

    // replace old file and media record with new ones, break associations
    // will also delete any existing variants of the replaced media record
    $uploader->onDuplicateReplaceWithVariants();

    // cancel upload, throw an exception
    $uploader->onDuplicateError();


Validation
--------------------

The ``MediaUpload`` will perform a number of validation checks on the source file. If any of the checks fail, a ``Plank\Mediable\MediaUploadException`` will be thrown with a message indicating why the file was rejected.


You can override the most validation configuration values set in ``config/mediable.php`` on a case-by-case basis using the same fluent interface.

::

    <?php
    $media = MediaUploader::fromSource($request->file('image'))

        // model class to use
        ->setModelClass(MediaSubclass::class)

        // maximum filesize in bytes
        ->setMaximumSize(99999)

        // whether the aggregate type must match both the MIME type and extension
        ->setStrictTypeChecking(true)

        // whether to allow the 'other' aggregate type
        ->setAllowUnrecognizedTypes(true)

        // only allow files of specific MIME types
        ->setAllowedMimeTypes(['image/jpeg'])

        // only allow files of specific extensions
        ->setAllowedExtensions(['jpg', 'jpeg'])

        // only allow files of specific aggregate types
        ->setAllowedAggregateTypes(['image'])

        ->upload();

You can also validate the file without uploading it by calling the ``verifyFile`` method.
If the file does not pass validation, an instance of ``Plank\Mediable\MediaUploadException`` will be thrown

::

    <?php
    $media = MediaUploader::fromSource($request->file('image'))

        // model class to use
        ->setModelClass(MediaSubclass::class)

        // maximum filesize in bytes
        ->setMaximumSize(99999)

        // only allow files of specific MIME types
        ->setAllowedMimeTypes(['image/jpeg'])

        ->verifyFile()


Alter Model before upload
-------------------------

You can manipulate the model before it's saved by passing a callable to the ``beforeSave`` method.
The callback takes two params, ``$model`` an instance of ``Plank\Mediable\Media`` the current model and ``$source`` an instance of ``Plank\Mediable\SourceAdapters\SourceAdapterInterface`` the current source.

::

    <?php
    $media = MediaUploader::fromSource($request->file('image'))

        // model class to use
        ->setModelClass(CustomMediaClass::class)

        // pass the callable
        ->beforeSave(function (Media $model, SourceAdapterInterface $source) {
            $model->setAttribute('customAttribute', 'value')
        })

        ->upload()


Visibility
--------------------

In addition to setting visibility on :ref:`Disks as a whole <disk_visibility>`, you can also specify whether a file should be publicly viewable on a file by file basic

::

    <?php
    MediaUploader::fromSource($request->file('image'))
        ->makePrivate() // Disable public access
        ->makePublic() // Default behaviour
        ->upload()

Options
--------------------

You can also specify additional option flags to be passed to the underlying filesystem adapter. This is particularly useful when dealing with cloud storage such as S3.

::

    <?php
    MediaUploader::fromSource($request->file('image'))
        ->withOptions(['Cache-Control' => 'max-age=3600'])
        ->upload();

Handling Exceptions
--------------------

If you want to return more granular HTTP status codes when a ``Plank\Mediable\MediaUploadException`` is thrown, you can use the ``Plank\Mediable\HandlesMediaUploadExceptions`` trait in your app's `Exceptions\Handler` or in your controller. For example, if you have set a maximum file size, an 413 HTTP response code (Request Entity Too Large) will be returned instead of a 500.

Call the ``transformMediaUploadException`` method as part of the ``render`` method of the exception handler, and a ``HttpException`` with the appropriate status code will be returned. Take a look at the ``HandlesMediaExceptions`` source code for the table of associated status codes and exceptions.

::

    <?php

    namespace App\Exceptions;

    use Plank\Mediable\HandlesMediaUploadExceptions;

    class Handler
    {
        use HandlesMediaUploadExceptions;

        public function render($request, $e)
        {
            $e = $this->transformMediaUploadException($e);

            return parent::render($request, $e);
        }
    }

If you only want some actions to throw an ``HttpException``, you can apply the trait to the controller instead.

::

    <?php

    class ExampleController extends Controller
    {
        use HandlesMediaUploadExceptions;

        public function upload(Request $request)
        {
            try{
                MediaUploader::fromSource($request->file('file'))
                    ->toDestination(...)
                    ->upload();
            }catch(MediaUploadException $e){
                throw $this->transformMediaUploadException($e);
            }
        }
    }

Importing Files
--------------------

If you need to create a media record for a file that is already in place on the desired filesystem disk, you can use one the import methods instead.

::

    <?php
    $media = MediaUploader::import($disk, $directory, $filename, $extension);
    // or
    $media = MediaUploader::importPath($disk, $path);

If you have string file data, you can import it using the `fromString` method.

::

    <?php
    // Encoded image converted to string
    $jpg = Image::make('https://www.plankdesign.com/externaluse/plank.png')->encode('jpg');

    MediaUploader::fromString($jpg)
        ->toDestination(...)
        ->upload();

Replacing Files
--------------------

If you need to swap out the file belonging to a ``Media`` record, you can use the ``replace()`` method. This will upload the file and update the existing record while maintaining any attachments to other models.

::

    <?php
    $media = Media::find($id);

    MediaUploader::fromSource($source)
        ->replace($media);



Updating Files
---------------

If a file has changed on disk, you can re-evaluate its attributes with the ``update()`` method. This will reassign the media record's ``mime_type``, ``aggregate_type`` and ``size`` attributes and will save the changes to the database, if any.

::

    <?php
    MediaUploader::update($media);
