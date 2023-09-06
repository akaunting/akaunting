Stream Output
===============

Stream to S3 Bucket
---------------

.. code-block:: php

    use Aws\S3\S3Client;
    use Aws\Credentials\CredentialProvider;
    use ZipStream\ZipStream;

    $bucket = 'your bucket name';
    $client = new S3Client([
        'region' => 'your region',
        'version' => 'latest',
        'bucketName' => $bucket,
        'credentials' => CredentialProvider::defaultProvider(),
    ]);
    $client->registerStreamWrapper();

    $zipFile = fopen("s3://$bucket/example.zip", 'w');

    $zip = new ZipStream(
        enableZip64: false,
        outputStream: $zipFile,
    );

    $zip->addFile(
        fileName: 'file1.txt',
        data: 'File1 data',
    );
    $zip->addFile(
        fileName: 'file2.txt',
        data: 'File2 data',
    );
    $zip->finish();

    fclose($zipFile);
