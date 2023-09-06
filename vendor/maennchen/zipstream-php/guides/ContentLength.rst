Adding Content-Length header
=============

Adding a ``Content-Length`` header for ``ZipStream`` can be achieved by
using the options ``SIMULATION_STRICT`` or ``SIMULATION_LAX`` in the
``operationMode`` parameter.

In the ``SIMULATION_STRICT`` mode, ``ZipStream`` will not allow to calculate the
size based on reading the whole file. ``SIMULATION_LAX`` will read the whole
file if neccessary.

``SIMULATION_STRICT`` is therefore useful to make sure that the size can be
calculated efficiently.

.. code-block:: php
    use ZipStream\OperationMode;
    use ZipStream\ZipStream;

    $zip = new ZipStream(
        operationMode: OperationMode::SIMULATE_STRICT, // or SIMULATE_LAX
        defaultEnableZeroHeader: false,
        sendHttpHeaders: true,
        outputStream: $stream,
    );

    // Normally add files
    $zip->addFile('sample.txt', 'Sample String Data');

    // Use addFileFromCallback and exactSize if you want to defer opening of
    // the file resource
    $zip->addFileFromCallback(
        'sample.txt',
        exactSize: 18,
        callback: function () {
            return fopen('...');
        }
    );

    // Read resulting file size
    $size = $zip->finish();
    
    // Tell it to the browser
    header('Content-Length: '. $size);
    
    // Execute the Simulation and stream the actual zip to the client
    $zip->executeSimulation();

