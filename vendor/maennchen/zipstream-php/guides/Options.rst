Available options
===============

Here is the full list of options available to you. You can also have a look at
``src/ZipStream.php`` file.

.. code-block:: php

    use ZipStream\ZipStream;

    require_once 'vendor/autoload.php';

    $zip = new ZipStream(
        // Define output stream
        // (argument is eiter a resource or implementing
        // `Psr\Http\Message\StreamInterface`)
        //
        // Setup with `psr/http-message` & `guzzlehttp/psr7` dependencies
        // required when using `Psr\Http\Message\StreamInterface`.
        outputStream: $filePointer,

        // Set the deflate level (default is 6; use -1 to disable it)
        defaultDeflateLevel: 6,

        // Add a comment to the zip file
        comment: 'This is a comment.',

        // Send http headers (default is true)
        sendHttpHeaders: false,

        // HTTP Content-Disposition.
        // Defaults to 'attachment', where FILENAME is the specified filename.
        // Note that this does nothing if you are not sending HTTP headers.
        contentDisposition: 'attachment',

        // Output Name for HTTP Content-Disposition
        // Defaults to no name
        outputName: "example.zip",

        // HTTP Content-Type.
        // Defaults to 'application/x-zip'.
        // Note that this does nothing if you are not sending HTTP headers.
        contentType: 'application/x-zip',

        // Set the function called for setting headers.
        // Default is the `header()` of PHP
        httpHeaderCallback: header(...),

        // Enable streaming files with single read where general purpose bit 3
        // indicates local file header contain zero values in crc and size
        // fields, these appear only after file contents in data descriptor
        // block.
        // Set to true if your input stream is remote
        // (used with addFileFromStream()).
        // Default is false.
        defaultEnableZeroHeader: false,

        // Enable zip64 extension, allowing very large archives
        // (> 4Gb or file count > 64k)
        // Default is true
        enableZip64: true,

        // Flush output buffer after every write
        // Default is false
        flushOutput: true,
    );
