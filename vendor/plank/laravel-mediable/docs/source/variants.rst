Image Variants
============================================

.. highlight:: php

.. _variants:

Laravel-Mediable integrates the `intervention/image <http://image.intervention.io/>`_ library to make it easy to manipulate image files and create numerous variations for different purposes.

Configure Intervention/image ImageManager
-----------------------------------------

By default, intervention/image will use the `GD <https://www.php.net/manual/en/book.image.php>`_ library driver. If you intend to use the additional features of the `ImageMagick <https://www.php.net/manual/en/book.imagick.php>`_ driver, you should make sure that the PHP extension is installed and the correct configuration is bound to the Laravel service container.

::

    <?php

    use Intervention\Image\ImageManager;

    class AppServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->app->bind(
                ImageManager::class,
                function() {
                    return new ImageManager(['driver' => 'imagick']);
                }
            );
        }
    }

Defining Variant Manipulations
------------------------------

Image Manipulation Callback
^^^^^^^^^^^^^^^^^^^^^^^^^^^

Before variants can be created, the manipulations to be applied to the images need to be defined. This should be done as part of the application boot step.

::

    <?php
    use Plank\Mediable\Facades\ImageManipulator;
    use Plank\Mediable\ImageManipulation;
    use Intervention\Image\Image;

    class AppServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            ImageManipulator::defineVariant(
                'thumb',
                ImageManipulation::make(function (Image $image, Media $originalMedia) {
                    $image->fit(32, 32);
                })->toPngFormat()
            );

            ImageManipulator::defineVariant(
                'bw-square',
                ImageManipulation::make(function (Image $image, Media $originalMedia) {
                    $image->fit(128, 128)->greyscale();
                })
            );
        }
    }

Each variant definition must contain a name and an instance of the ``ImageManipulation`` class, which contains the instructions for converting the image into the desired derivative form.

First and foremost, each manipulation requires a callback which contains instructions on how the image should be modified. The callback will be passed an instance of ``Intervention\Image\Image`` and the original ``Media`` record, and may use any of the methods available to the library to change its form. See the `intervention/image documentation <http://image.intervention.io/>`_ for available methods.

Output Formats
^^^^^^^^^^^^^^

The ImageManipulation class also offers a fluent interface for defining how the modified file should be output. If not specified, will attempt to use the same format as the original file, based on the ``mime_type`` and ``extension`` attributes of the original Media record.

::

    <?php
    $manipulation->outputJpegFormat();
    $manipulation->outputPngFormat();
    $manipulation->outputGifFormat();
    $manipulation->outputTiffFormat();
    $manipulation->outputBmpFormat();
    $manipulation->outputWebpFormat();
    $manipulation->setOutputFormat($format);

If outputting to JPEG format, it is also possible to set the desired level of lossy compression, from 0 (low quality, smaller file size) to 100 (high quality, larger file size). Defaults to 90. This value is ignored by other formats.

::

    <?php
    $manipulation->outputJpegFormat()->setOutputQuality(50);


.. note::
    Intervention/image requires different dependency libraries to be installed in order to output different format. Review the `intervention image documentation <http://image.intervention.io/getting_started/formats>`_ for more details.

Output Destination
^^^^^^^^^^^^^^^^^^

By default, variants will be created in the same disk and directory as the original file, with a filename that includes the variant name as as suffix. You can choose to customize the output disk, directory and filename.

::

    <?php
    $manipulation->toDisk('uploads');
    $manipulation->toDirectory('files/variants');

    // shorthand for the above
    $manipulation->toDestination('uploads', 'files/variants');

    $manipulation->useFilename('my-custom-filename');
    $manipulation->useHashForFilename();
    $manipulation->useOriginalFilename(); //restore default behaviour

If another file exists at the output destination, the ImageManipulator will attempt to find a unique filename by appending an incrementing number. This can be configured to throw an exception instead if a conflict is discovered.

::

    <?php
    $manipulation->onDuplicateIncrement(); // default behaviour
    $manipulation->onDuplicateError();

File Visibility
^^^^^^^^^^^^^^^

By default, newly created variants will use the default filesystem visibility of the destination filesystem disk. To modify this, you may use one of the following methods.

::

    <?php
    $manipulation->makePrivate();
    $manipulation->makePublic();
    // to copy the visibility of the original media file
    $manipulation->matchOriginalVisibility();

Before Save Callback
^^^^^^^^^^^^^^^^^^^^

You can specify a callback which will be invoked after the image manipulation is processed, but before the file is written to disk and a ``Media`` record is written to the database. The callback will be passed the populated ``Media`` record, which can be modified. This can also be used to set additional fields.

::

    <?php
    $manipulation->beforeSave(function(Media $media) {
        $media->directory = 'thumbnails';
        $media->someOtherField = 'potato';
    });

.. note:: Modifying the disk, directory, filename, or extension fields will cause the output destination to be changed accordingly. Duplicates will be checked again against the new location.

Creating Variants
-----------------

Variants can be created from the ``ImageManipulator`` class. This will create a new file derived from applying the manipulation to the original. A new Media record will be create to represent the new file.

::

    <?php
    use Plank\Mediable\Facades\ImageManipulator;

    $variantMedia = ImageManipulator::createImageVariant($originalMedia, 'thumbnail');


Depending on the size of the files and the nature of the manipulations, creating variants may be a time consuming operation. As such, it may be more beneficial to perform the operation asynchronously. The ``CreateImageVariants`` job can be used to easily queue variants to be processed. A collection of ``Media`` records and multiple variant names can be provided in order to process the creation of several variants as part of the same worker process.

::

    <?php
    use Plank\Mediable\Jobs\CreateImageVariants;
    use Illuminate\Database\Eloquent\Collection;

    // will produce one variant
    CreateImageVariants::dispatch($media, ['square']);

    // will produce 4 variants (2 of each media)
    CreateImageVariants::dispatch(
        new Collection([$media1, $media2]),
        ['square', 'bw-square']
    );

Recreating Variants
^^^^^^^^^^^^^^^^^^^

If a variant with the requested variant name already exists for the provided media, the ``ImageManipulator`` will skip over it. If you need to regenerate a variant (e.g. because the manipulations changed), you can tell the ``ImageManipulator`` to recreate the variant by passing an additional ``$forceRecreate`` parameter.

::

    <?php
    $variantMedia = ImageManipulator::createImageVariant($originalMedia, 'thumbnail', true);
    CreateImageVariants::dispatch($media, ['square', 'bw-square'], true);

Doing so will cause the original file to be deleted, and a new one created at the specified output destination. The variant record will retain its primary key and any associations, but its attributes will be updated as necessary.

Tagging Variants
^^^^^^^^^^^^^^^^

When defining variants, it is possible to pass one or more "tags" to group the definitions in order to more easily retrieve all of the ones applicable to a specific purpose.

::

    <?php
    use Plank\Mediable\Jobs\CreateImageVariants;

    ImageManipulator::defineVariant(
        'avatar-small',
        ImageManipulation::make(/* ... */),
        ['avatar']
    );

    ImageManipulator::defineVariant(
        'avatar-large',
        ImageManipulation::make(/* ... */),
        ['avatar']
    );

    // generate all 'avatar' variants
    CreateImageVariants::dispatch(
        $mediaCollection,
        ImageManipulator::getVariantNamesByTag('avatar')
    );


Using Variants
--------------

For all intents and purposes, variants are fully functional ``Media`` records. They can be attached to ``Mediable`` models, output paths and URLs, be moved and copied, etc.

However, variants also remember the name of the variant definition and the original ``Media`` record from which they were created. This information can be used to find the right file for a given context. This package takes an un-opinionated approach to how your application should use the variants that you create. You can either attach variants directly to your models, or attach the original and then navigate to the appropriate variant.

::

    <?php
    $src = $post->getMedia('feature')
        ->findVariant('thumbnail')
        ->getUrl()

Original vs. Variants
^^^^^^^^^^^^^^^^^^^^^

An "original" ``Media`` record is one the one that was initially uploaded to the server. A variant is the derivative that was created by manipulating the original. You can distinguish them with these methods:

::

    <?php
    // check if the Media is an original
    $media->isOriginal();

    // check if the Media is any kind of variant
    $media->isVariant();

    // check if the Media is a specific kind of variant
    $media->isVariant('thumbnail');

    // read the kind of the variant, will be `null` for originals
    $media->variant_name

Navigating between variants
^^^^^^^^^^^^^^^^^^^^^^^^^^^

From any instance of a Media, you can jump to any other in the same variant family using the following methods. If you are already dealing with the variant that you are requesting, it will return itself.

::

    <?php
    $original = $media->findOriginal();
    $variant = $media->findVariant('thumbnail');
    $bool = $media->hasVariant('thumbnail');

.. warning::
    Avoid chaining find calls from one ``Media`` to the next. To avoid unnecessary database calls, it is best to always start from the same initial node.

List All Variants
^^^^^^^^^^^^^^^^^

You can also list out all of the variants and the original of a variant family as a keyed dictionary.

::

    <?php

    // excluding the current model
    $collection = $media->getAllVariants();

    // including the current model
    $collection = $media->getAllVariantsAndSelf();

    /* outputs
    [
        'original' => Media{},
        'thumbnail' => Media{},
        'large' => Media{}
        etc.
    ]
    */

Manual Adjustments
^^^^^^^^^^^^^^^^^^

If necessary, you can also promote a variant to become an original. Doing so clears its variant name and detaches it from the rest of its former variant family.

::

    <?php
    $variant->makeOriginal()->save();

To manually indicate that one ``Media`` record is a variant of another

::

    <?php
    $media->makeVariantOf($otherMedia, 'small')->save();
    $media->makeVariantOf($otherMediaId, 'small')->save();

.. note::
    A variant family is a set, not a tree. If a variant is created from or associated to another variant, they will share the same original Media.

Eager Loading
^^^^^^^^^^^^^

When accessing media variants from a collection of Mediable records, be sure to eager load them when possible to avoid the N+1 query problem.

::

    <?php
    // eager load
    $posts = Post::withMediaAndVariants($tags)->get();
    $posts = Post::withMediaAndVariantsMatchAll($tags)->get();

    // lazy eager load from a collection of Mediables
    $posts->loadMediaAndVariants($tags);
    $posts->loadMediaAndVariantsMatchAll($tags);

    // lazy eager load from a single Mediable model
    $post->loadMediaAndVariants($tags);
    $post->loadMediaAndVariantsMatchAll($tags);

