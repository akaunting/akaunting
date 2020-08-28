<?php

return [

    'next'                  => 'Next',
    'refresh'               => 'Refresh',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
        'language'          => 'Step 1/3 : Language Selection',
        'database'          => 'Step 2/3 : Database Setup',
        'settings'          => 'Step 3/3 : Company and Admin Details',
    ],

    'language' => [
        'select'            => 'Select Language',
    ],

    'requirements' => [
        'enabled'           => ':feature needs to be enabled!',
        'disabled'          => ':feature needs to be disabled!',
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => ':directory directory needs to be writable!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
    ],

    'database' => [
        'hostname'          => 'Hostname',
        'username'          => 'Username',
        'password'          => 'Password',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Company Name',
        'company_email'     => 'Company Email',
        'admin_email'       => 'Admin Email',
        'admin_password'    => 'Admin Password',
    ],

    'error' => [
        'php_version'       => 'Error: Ask your hosting provider to use PHP :php_version or higher for both HTTP and CLI.',
        'connection'        => 'Error: Could not connect to the database! Please, make sure the details are correct.',
    ],

];
