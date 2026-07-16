<?php

return [

    'auth'                  => 'Autentikasi',
    'profile'               => 'Profil',
    'logout'                => 'Keluar',
    'login'                 => 'Masuk',
    'forgot'                => 'Lupa',
    'login_to'              => 'Masuk untuk memulai sesi Anda',
    'remember_me'           => 'Ingat Saya',
    'forgot_password'       => 'Lupa kata sandi',
    'reset_password'        => 'Atur Ulang Kata Sandi',
    'change_password'       => 'Ubah Kata Sandi',
    'enter_email'           => 'Masukkan Alamat E-mel Anda',
    'current_email'         => 'E-mel Semasa',
    'reset'                 => 'Atur Ulang',
    'never'                 => 'jangan pernah',
    'landing_page'          => 'Halaman Muka',
    'personal_information'  => 'Informasi pribadi',
    'register_user'         => 'Pendaftaran Pengguna',
    'register'              => 'Daftar',

    'form_description' => [
        'personal'          => 'Pautan jemputan akan dihantar kepada pengguna baharu, jadi pastikan alamat e-mel adalah betul. Mereka akan dapat memasukkan kata laluan mereka.',
        'assign'            => 'Pengguna akan mempunyai akses kepada syarikat yang dipilih. Anda boleh menyekat kebenaran dari halaman <a href=":url" class="border-b border-black">peranan</a>.',
        'preferences'       => 'Pilih bahasa lalai pengguna. Anda juga boleh menetapkan halaman pendaratan selepas pengguna log masuk.',
    ],

    'password' => [
        'pass'              => 'Kata Sandi',
        'pass_confirm'      => 'Konfirmasi Kata Sandi',
        'current'           => 'Kata Sandi',
        'current_confirm'   => 'Konfirmasi Kata Sandi',
        'new'               => 'Kata Sandi Baru',
        'new_confirm'       => 'Konfirmasi Kata Sandi Baru',
    ],

    'error' => [
        'self_delete'       => 'Ralat: Tidak boleh memadam diri sendiri!',
        'self_disable'      => 'Ralat: Tidak boleh menonaktifkan diri sendiri!',
        'unassigned'        => 'Ralat: Tidak boleh nyah tugaskan syarikat! Syarikat :company mesti ditugaskan kepada sekurang-kurangnya seorang pengguna.',
        'no_company'        => 'Ralat: Tiada syarikat ditugaskan kepada akaun anda. Sila hubungi pentadbir sistem.',
    ],

    'login_redirect'        => 'Pengesahan selesai! Anda sedang dihalakan...',
    'failed'                => 'Maklumat log masuk ini tidak sepadan dengan rekod kami.',
    'throttle'              => 'Terlalu banyak percubaan log masuk. Sila cuba semula dalam :seconds saat.',
    'disabled'              => 'Akaun ini telah dinonaktifkan. Sila hubungi pentadbir sistem.',

    'notification' => [
        'message_1'         => 'Anda menerima e-mel ini kerana kami menerima permintaan tetapan semula kata laluan untuk akaun anda.',
        'message_2'         => 'Jika anda tidak meminta tetapan semula kata laluan, tiada tindakan lanjut diperlukan.',
        'button'            => 'Tetap Semula Kata Laluan',
    ],

    'invitation' => [
        'message_1'         => 'Anda menerima e-mel ini kerana anda dijemput untuk menyertai Akaunting.',
        'message_2'         => 'Jika anda tidak ingin menyertai, tiada tindakan lanjut diperlukan.',
        'button'            => 'Mula',
    ],

    'information' => [
        'invoice'           => 'Cipta invois dengan mudah',
        'reports'           => 'Dapatkan detail laporan',
        'expense'           => 'Lacak semua perbelanjaan',
        'customize'         => 'Sesuaikan Akaunting Anda',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Pentadbir',
            'description'   => 'Mereka mendapat akses penuh ke Akaunting anda termasuk pelanggan, invois, laporan, tetapan, dan aplikasi.',
        ],
        'manager' => [
            'name'          => 'Pengurus',
            'description'   => 'Mereka mendapat akses penuh ke Akaunting anda, tetapi tidak boleh mengurus pengguna dan aplikasi.',
        ],
        'customer' => [
            'name'          => 'Pelanggan',
            'description'   => 'Mereka boleh mengakses Portal Klien dan membayar invois mereka secara dalam talian melalui kaedah bayaran yang anda tetapkan.',
        ],
        'accountant' => [
            'name'          => 'Akauntan',
            'description'   => 'Mereka boleh mengakses invois, transaksi, laporan, dan mencipta entri jurnal.',
        ],
        'employee' => [
            'name'          => 'Pekerja',
            'description'   => 'Mereka boleh mencipta tuntutan perbelanjaan dan menjejak masa untuk projek yang ditugaskan, tetapi hanya boleh melihat maklumat mereka sendiri.',
        ],
    ],

];
