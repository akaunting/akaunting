<?php

return [

    'next'                  => 'Selanjutnya',
    'refresh'               => 'Perbarui',

    'steps' => [
        'requirements'      => 'Sila minta penyedia hosting anda membetulkan ralat!',
        'language'          => 'Langkah 1/3 : Pemilihan Bahasa',
        'database'          => 'Langkah 2/3 : Persediaan Pangkalan Data',
        'settings'          => 'Langkah 3/3 : Butiran Syarikat dan Pentadbir',
    ],

    'language' => [
        'select'            => 'Pilih bahasa',
    ],

    'requirements' => [
        'enabled'           => ':feature perlu diaktifkan!',
        'disabled'          => ':feature perlu dinonaktifkan!',
        'extension'         => ':extension ekstensi perlu diinstal dan dimuat!',
        'directory'         => ':directory direktori perlu ditulis!',
        'executable'        => 'Fail PHP CLI tidak ditakrifkan/berfungsi atau bukan versi :php_version atau lebih tinggi! Sila minta penyedia hosting anda menetapkan pembolehubah persekitaran PHP_BINARY atau PHP_PATH dengan betul.',
        'npm'               => '<b>Fail JavaScript tiada!</b> <br><br><span>Anda patut menjalankan arahan <em class="underline">npm install</em> dan <em class="underline">npm run dev</em>.</span>',
    ],

    'database' => [
        'hostname'          => 'Nama host',
        'username'          => 'Nama pengguna',
        'password'          => 'Kata Sandi',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Nama Perusahaan',
        'company_email'     => 'E-mel Syarikat',
        'admin_email'       => 'E-mel Pentadbir',
        'admin_password'    => 'Kata Sandi Admin',
    ],

    'error' => [
        'php_version'       => 'Ralat: Minta penyedia hosting anda menggunakan PHP :php_version atau lebih tinggi untuk HTTP dan CLI.',
        'connection'        => 'Ralat: Tidak dapat menyambung ke pangkalan data! Sila pastikan butirannya adalah betul.',
    ],

    'update' => [
        'core'              => 'Versi baharu Akaunting tersedia! Sila kemas kini <a href=":url">pemasangan anda.</a>',
        'module'            => ':module versi baharu tersedia! Sila kemas kini <a href=":url">pemasangan anda.</a>',
    ],
];
