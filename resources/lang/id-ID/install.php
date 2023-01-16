<?php

return [

    'next'                  => 'Selanjutnya',
    'refresh'               => 'Perbarui',

    'steps' => [
        'requirements'      => 'Silakan meminta ke penyedia hosting Anda untuk memperbaiki kesalahan!',
        'language'          => 'Langkah 1/3 : Seleksi Bahasa',
        'database'          => 'Atur Database',
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
        'executable'        => 'Berkas PHP CLI belum didefinisikan/bekerja atau bukan versi :php_version ke atas. Mohon tanyakan ke penyedia hosting untuk mengatur variabel PHP_BINARY atau PHP_PATH dengan benar.',
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
        'php_version'       => 'Terjadi Kesalahan: Minta ke penyedia hosting Anda untuk menggunakan PHP: php_version atau lebih tinggi untuk HTTP dan CLI.',
        'connection'        => 'Kesalahan: Tidak dapat terhubung ke database! Silahkan, pastikan bahwa rinciannya benar.',
    ],

];
