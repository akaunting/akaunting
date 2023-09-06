Using Media
============

.. highlight:: php

Media Paths
---------------------

``Media`` records keep track of the location of their file and are able to generate a number of paths relative to the file. Consider the following example, given a ``Media`` instance with the following attributes:


::

	[
		'disk' => 'uploads',
		'directory' => 'foo/bar',
		'filename' => 'picture',
		'extension' => 'jpg'
		// ...
	];

The following attributes and methods would be exposed:

::

	<?php
	$media->getAbsolutePath();
	// /var/www/site/public/uploads/foo/bar/picture.jpg

	$media->getDiskPath();
	// foo/bar/picture.jpg

	$media->directory;
	// foo/bar

	$media->basename;
	// picture.jpg

	$media->filename;
	// picture

	$media->extension;
	// jpg

URLs and Downloads
---------------------

URLs can be generated for Media stored on a public disk and set to public visibility.

::

	$media->getUrl();
	// http://localhost/uploads/foo/bar/picture.jpg

`$media->getUrl()` will throw an exception if the file or its disk has its visibility set to private. You can check if it is safe to generate a url for a record with the `$media->isPubliclyAccessible()` method.

For private files stored on an Amazon S3 disk, it is possible to generate a temporary signed URL to allow authorized users the ability to download the file for a specified period of time.

::

    <?php
    $media->getTemporaryUrl(Carbon::now->addMinutes(5));

For private files, it is possible to expose them to authorized users by streaming the file from the server.

::

    <?php
    return response()->streamDownload(
        function() use ($media) {
            $stream = $media->stream();
            while($bytes = $stream->read(1024)) {
                echo $bytes;
            }
        },
        $media->basename,
        [
            'Content-Type' => $media->mime_type,
            'Content-Length' => $media->size
        ]
    );

Querying Media
---------------------

If you need to query the media table directly, rather than through associated models, the Media class exposes a few helpful methods for the query builder.

::

	<?php
	Media::inDirectory('uploads', 'foo/bar');
	Media::inOrUnderDirectory('uploads', 'foo');
	Media::forPathOnDisk('uploads', 'foo/bar/picture.jpg');
	Media::whereBasename('picture.jpg');


Moving Media
---------------------

You should taking caution if manually changing a media record's attributes, as your record and file could go out of sync.

You can change the location of a media file on disk.

::

	<?php
	$media->move('new/directory');
	$media->move('new/directory', 'new-filename');
	$media->rename('new-filename');
    $media->moveToDisk('uploads', 'new/directory', 'new-filename');

Copying Media
---------------------

You can duplicate a media file to a different location on disk with the ``copyTo()`` method. Doing so will create a new ``Media`` record for the new file. If a filename is not provided, the new file will copy the original filename.

::

    <?php
    $newMedia = $media->copyTo('new/directory');
    $newMedia = $media->copyTo('new/directory', 'new-filename');
    $newMedia = $media->copyToDisk('uploads', 'new/directory', 'new-filename');

:Note: Both ``moveToDisk()`` and ``copyToDisk()`` support passing an additional ``$options`` argument with flags to be passed to the underlying filesystem adapter of the destination disk.

Deleting Media
---------------------

You can delete media with standard Eloquent model ``delete()`` method. This will also delete the file associated with the record and detach any associated ``Mediable`` models.

::

	<?php
	$media->delete();


**Note**: The ``delete()`` method on the query builder *will not* delete the associated file. It will still purge relationships due to the cascading foreign key.

::

	<?php
	Media::where(...)->delete(); //will not delete files

Soft Deletes
^^^^^^^^^^^^

If you subclass the ``Media`` class and add Laravel's ``SoftDeletes`` trait, the media will only delete its associated file and detach its relationship if ``forceDelete()`` is used.

You can change the ``detach_on_soft_delete`` setting to ``true`` in ``config/mediable.php`` to have relationships automatically detach when either the ``Media`` record or ``Mediable`` model are soft deleted.

Setting Visibility
---------------------

You can update the visibility of a `Media` record's file

::

	<?php
	$media->makePublic();
	$media->makePrivate();
