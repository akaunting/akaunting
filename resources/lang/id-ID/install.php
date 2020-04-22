<?php

return [

    'next'                  => 'Berikutnya',
    'refresh'               => 'Muat Ulang',

    'steps' => [
        'requirements'      => 'Silakan meminta penyedia hosting Anda untuk memperbaiki kesalahan!',
        'language'          => 'Langkah 1/3 : Seleksi Bahasa',
        'database'          => 'Langkah 2/3 : Setup basis data',
        'settings'          => 'Langkah 3/3: Detail Perusahaan dan Admin',
    ],

    'language' => [
        'select'            => 'Pilih bahasa',
    ],

    'requirements' => [
        'enabled'           => ':feature perlu diaktifkan!',
        'disabled'          => ':feature perlu dinonaktifkan!',
        'extension'         => ': ekstensi ekstensi perlu diinstal dan dimuat!',
        'directory'         => ':directory direktori perlu ditulis!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
    ],

    'database' => [
        'hostname'          => 'Nama host',
        'username'          => 'Nama pengguna',
        'password'          => 'Kata Sandi',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Nama Perusahaan',
        'company_email'     => 'Email Perusahaan',
        'admin_email'       => 'Email Admin',
        'admin_password'    => 'Kata Sandi Admin',
    ],

    'error' => [
        'connection'        => 'Kesalahan: Tidak dapat terhubung ke database! Tolong, pastikan detilnya benar.',
    ],

];
