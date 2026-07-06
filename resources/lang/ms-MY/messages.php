<?php

return [

    'success' => [
        'added'             => ':type ditambahkan!',
        'created'           => ':type dibuat!',
        'updated'           => ':type diperbaharui!',
        'deleted'           => ':type dihapus!',
        'duplicated'        => ':type duplikat!',
        'imported'          => ':type diimpor!',
        'import_queued'     => 'Impor :type telah dijadwalkan! Anda akan menerima sebuah email ketika hal tersebut selesai.',
        'exported'          => ':type diekspor!',
        'export_queued'     => 'Ekspor :type dari halaman ini telah dijadwalkan! Anda akan menerima sebuah email ketika hal tersebut siap untuk diunduh.',
        'download_queued'   => 'Unduhan :type dari halaman ini telah dijadwalkan! Anda akan menerima sebuah email ketika hal tersebut siap untuk diunduh.',
        'enabled'           => ':type diaktifkan!',
        'disabled'          => ':type dinonaktifkan!',
        'connected'         => ':type terhubung!',
        'invited'           => ':type diundang!',
        'ended'             => ':type berakhir!',

        'clear_all'         => 'Bagus! Anda telah membersihkan semua :type Anda.',
    ],

    'error' => [
        'over_payment'      => 'Error: Bayaran tidak ditambahkan! Jumlah yang Anda masukan melebihi nilai total :amount',
        'not_user_company'  => 'Kesalahan: Anda tidak diperbolehkan mengelola perusahaan ini!',
        'customer'          => 'Galat: Pengguna tidak dibuat! :name telah menggunakan alamat email ini.',
        'no_file'           => 'Kesalahan: Tidak ada file dipilih!',
        'last_category'     => 'Error: Tidak dapat menghapus kategori <b>:type</b> terakhir!',
        'transfer_category' => 'Error: Tidak dapat menghapus kategori transfer <b>:type</b>!',
        'change_type'       => 'Kesalahan: Tidak dapat mengubah jenis karena memiliki :text terkait!',
        'invalid_apikey'    => 'Galat: Token API yang dimasukkan tidak sah!',
        'empty_apikey'      => 'Galat: Anda belum memasukkan Kunci API! <a href=":url" class="font-bold underline underline-offset-4">Klik di sini</a> untuk memasukkan Kunci API Anda.',
        'import_column'     => 'Kesalahan: :message Nama kolom: :column. Nomor baris: :line.',
        'import_sheet'      => 'Error: Nama sheet tidak valid. Mohon untuk memeriksa contoh file yang tersedia.',
        'same_amount'       => 'Error: Jumlah total pembagian harus sama persis dengan :transaction total: :amount',
        'over_match'        => 'Error: :type tidak terhubung! Jumlah yang Anda masukkan tidak boleh melebihi total bayaran: :amount',
    ],

    'warning' => [
        'deleted'           => 'Peringatan: Anda tidak boleh menghapus <b>:name</b> karena memiliki :text terkait.',
        'disabled'          => 'Peringatan: Anda tidak boleh menonaktifkan<b>:name</b> karena memiliki :text terkait.',
        'reconciled_tran'   => 'Perhatian: tidak diperbolehkan merubah/menghapus transaksi karena sudah direkonsilasi',
        'reconciled_doc'    => 'Perhatian: tidak diperbolehkan merubah/menghapus :type karena transaksi sudah direkonsilasi',
        'disable_code'      => 'Peringatan: Anda tidak diizinkan untuk menonaktifkan atau mengubah kurs <b>:name</b> karena memiliki keterkaitan dengan :text.',
        'payment_cancel'    => 'Peringatan: kamu tidak dapat membatalkan :method bayaran!',
        'missing_transfer'  => 'Peringatan: Tidak ada transfer yang terkait dengan transaksi ini. Anda harus mempertimbangkan untuk menghapus transaksi ini.',
        'connect_tax'       => 'Peringatan: :type ini memiliki jumlah pajak. Pajak yang ditambahkan ke :type tidak dapat dihubungkan, sehingga pajak akan ditambahkan ke total dan dihitung sesuai itu.',
        'contact_change'    => 'Peringatan: Anda tidak diizinkan mengubah kontak pada :type yang sudah dikirim, diterima, atau dibayar!',
    ],

];
