<?php

# required: PHP 5.3+ and zlib extension

// ini option check
if (ini_get('phar.readonly')) {
    echo "php.ini: set the 'phar.readonly' option to 0 to enable phar creation\n";
    exit(1);
}

// output name
$pharName = 'Matrix.phar';

// target folder
$sourceDir = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

// default meta information
$metaData = array(
    'Author'      => 'Mark Baker <mark@lange.demon.co.uk>',
    'Description' => 'PHP Class for working with Matrix numbers',
    'Copyright'   => 'Mark Baker (c) 2013-' . date('Y'),
    'Timestamp'   => time(),
    'Version'     => '0.1.0',
    'Date'        => date('Y-m-d')
);

// cleanup
if (file_exists($pharName)) {
    echo "Removed: {$pharName}\n";
    unlink($pharName);
}

echo "Building phar file...\n";

// the phar object
$phar = new Phar($pharName, null, 'Matrix');
$phar->buildFromDirectory($sourceDir);
$phar->setStub(
<<<'EOT'
<?php
    spl_autoload_register(function ($className) {
        include 'phar://' . $className . '.php';
    });

    try {
        Phar::mapPhar();
    } catch (PharException $e) {
        error_log($e->getMessage());
        exit(1);
    }

    include 'phar://functions/sqrt.php';

    __HALT_COMPILER();
EOT
);
$phar->setMetadata($metaData);
$phar->compressFiles(Phar::GZ);

echo "Complete.\n";

exit();
