Usage with FlySystem
===============

For saving or uploading the generated zip, you can use the
`Flysystem <https://flysystem.thephpleague.com>`_ package, and its many
adapters.

For that you will need to provide another stream than the ``php://output``
default one, and pass it to Flysystem ``putStream`` method.

.. code-block:: php

    // Open Stream only once for read and write since it's a memory stream and
    // the content is lost when closing the stream / opening another one
    $tempStream = fopen('php://memory', 'w+');

    // Create Zip Archive
    $zipStream = new ZipStream(
        outputStream: $tempStream,
        outputName: 'test.zip',
    );
    $zipStream->addFile('test.txt', 'text');
    $zipStream->finish();

    // Store File
    // (see Flysystem documentation, and all its framework integration)
    // Can be any adapter (AWS, Google, Ftp, etc.)
    $adapter = new Local(__DIR__.'/path/to/folder');
    $filesystem = new Filesystem($adapter);

    $filesystem->writeStream('test.zip', $tempStream)

    // Close Stream
    fclose($tempStream);
