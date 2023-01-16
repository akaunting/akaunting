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
    'enter_email'           => 'Masukan Alamat Email Anda',
    'current_email'         => 'Email saat ini',
    'reset'                 => 'Atur Ulang',
    'never'                 => 'jangan pernah',
    'landing_page'          => 'Halaman Muka',
    'personal_information'  => 'Informasi pribadi',
    'register_user'         => 'Pendaftaran Pengguna',
    'register'              => 'Daftar',

    'form_description' => [
        'personal'          => 'Link undangan akan dikirimkan ke pengguna baru, pastikan email tertera benar. Mereka akan segera bisa memasukkan password.',
        'assign'            => 'Pengguna tersebut akan memiliki akses ke perusahaan yang dipilih. Anda dapat mengatur hak akses melalui halaman <a href=":url" class="border-b border-black">roles</a>.',
        'preferences'       => 'Pilih bahasa default untuk pengguna. Anda juga bisa tentukan untuk halaman muka setelah pengguna berhasil masuk',
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
        'self_delete'       => 'Kesalahan: Tidak dapat menghapus akun sendiri!',
        'self_disable'      => 'Kesalahan: Tidak dapat menonaktifkan akun sendiri!',
        'unassigned'        => 'Error: Tidak dapat membatalkan penetapan perusahaan! :company harus di tugaskan kepada minimal satu pengguna.',
        'no_company'        => 'Kesalahan: Tidak ada perusahaan yang ditunjuk ke akun Anda. Silakan hubungi administrator sistem.',
    ],

    'login_redirect'        => 'Verifikasi selesai! Anda sedang diarahkan...',
    'failed'                => 'Identitas ini tidak cocok dengan data kami.',
    'throttle'              => 'Terlalu banyak upaya masuk. Silahkan coba lagi dalam :seconds detik.',
    'disabled'              => 'Akun ini dinonaktifkan. Silahkan, hubungi administrator sistem.',

    'notification' => [
        'message_1'         => 'Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.',
        'message_2'         => 'Jika Anda tidak melakukan permintaan pengaturan ulang kata sandi, tindakan lebih lanjut tidak diperlukan.',
        'button'            => 'Atur ulang Kata Sandi',
    ],

    'invitation' => [
        'message_1'         => 'Anda menerima email ini karena anda diundang untuk mendaftar ke Akaunting.',
        'message_2'         => 'Jika Anda tidak ingin membuat sebuah akun, tidak diperlukan tindakan lebih lanjut.',
        'button'            => 'Mulai',
    ],

    'information' => [
        'invoice'           => 'Buat faktur instan',
        'reports'           => 'Dapatkan detail laporan',
        'expense'           => 'Lacak semua pengeluaran',
        'customize'         => 'Sesuaikan Akaunting Anda',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'Mereka memiliki akses penuh ke aplikasi termasuk ke area pelanggan, faktur, laporan, pengaturan dan apps.',
        ],
        'manager' => [
            'name'          => 'Manajer',
            'description'   => 'Mereka mendapat akses penuh ke aplikasi, tapi tidak bisa mengelola pengguna dan apps.',
        ],
        'customer' => [
            'name'          => 'Pelanggan',
            'description'   => 'Mereka bisa mengakses Portal Klien dan membayar online melalui metode pembayaran yang sudah anda tentukan',
        ],
        'accountant' => [
            'name'          => 'Akuntan',
            'description'   => 'Mereka bisa mengakses faktur, transaksi, laporan, dan membuat entri jurnal',
        ],
        'employee' => [
            'name'          => 'Karyawan',
            'description'   => 'Mereka dapat membuat klaim pengeluaran dan melacak waktu untuk proyek yang ditugaskan, tetapi hanya dapat melihat informasi mereka sendiri.',
        ],
    ],

];
