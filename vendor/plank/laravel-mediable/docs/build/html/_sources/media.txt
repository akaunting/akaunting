Using Media
============

.. highlight:: php

Media Paths & URLs
---------------------

``Media`` records keep track of the location of their file and are able to generate a number of paths and URLs relative to the file. Consider the following example, given a ``Media`` instance with the following attributes:


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

	$media->getUrl();
	// http://localhost/uploads/foo/bar/picture.jpg

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

You should taking caution if manually changing a media record's attributes, as you record and file could go out of sync.

You can change the location of a media file on disk. You cannot move a media to a different disk this way.

::

	<?php
	$media->move('new/directory');
	$media->move('new/directory', 'new-filename');
	$media->rename('new-filename');


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
