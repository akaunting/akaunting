<?php

return [

    'success' => [
        'added'             => ':type ditambah!',
        'created'           => ':type dicipta!',
        'updated'           => ':type dikemas kini!',
        'deleted'           => ':type dipadam!',
        'duplicated'        => ':type diduplikasi!',
        'imported'          => ':type diimport!',
        'import_queued'     => 'Import :type telah dijadualkan! Anda akan menerima e-mel apabila ia selesai.',
        'exported'          => ':type dieksport!',
        'export_queued'     => 'Eksport :type untuk halaman semasa telah dijadualkan! Anda akan menerima e-mel apabila ia sedia untuk dimuat turun.',
        'download_queued'   => 'Muat turun :type untuk halaman semasa telah dijadualkan! Anda akan menerima e-mel apabila ia sedia untuk dimuat turun.',
        'enabled'           => ':type diaktifkan!',
        'disabled'          => ':type dinonaktifkan!',
        'connected'         => ':type disambung!',
        'invited'           => ':type dijemput!',
        'ended'             => ':type ditamatkan!',

        'clear_all'         => 'Bagus! Anda telah mengosongkan semua :type anda.',
    ],

    'error' => [
        'over_payment'      => 'Ralat: Bayaran tidak ditambah! Jumlah yang anda masukkan melebihi jumlah keseluruhan: :amount',
        'not_user_company'  => 'Ralat: Anda tidak dibenarkan menguruskan syarikat ini!',
        'customer'          => 'Ralat: Pengguna tidak dicipta! :name sudah menggunakan alamat e-mel ini.',
        'no_file'           => 'Ralat: Tiada fail dipilih!',
        'last_category'     => 'Ralat: Tidak boleh memadam kategori <b>:type</b> terakhir!',
        'transfer_category' => 'Ralat: Tidak boleh memadam kategori pemindahan <b>:type</b>!',
        'change_type'       => 'Ralat: Tidak boleh menukar jenis kerana ia mempunyai :text yang berkaitan!',
        'invalid_apikey'    => 'Ralat: Kunci API yang dimasukkan tidak sah!',
        'empty_apikey'      => 'Ralat: Anda belum memasukkan Kunci API! <a href=":url" class="font-bold underline underline-offset-4">Klik di sini</a> untuk memasukkan Kunci API anda.',
        'import_column'     => 'Ralat: :message Nama lajur: :column. Nombor baris: :line.',
        'import_sheet'      => 'Ralat: Nama hela tidak sah. Sila semak fail contoh.',
        'same_amount'       => 'Ralat: Jumlah jumlah pisah mesti sama persis dengan jumlah :transaction: :amount',
        'over_match'        => 'Ralat: :type tidak disambung! Jumlah yang anda masukkan tidak boleh melebihi jumlah bayaran: :amount',
    ],

    'warning' => [
        'deleted'           => 'Amaran: Anda tidak dibenarkan memadam <b>:name</b> kerana ia mempunyai :text yang berkaitan.',
        'disabled'          => 'Amaran: Anda tidak dibenarkan menonaktifkan <b>:name</b> kerana ia mempunyai :text yang berkaitan.',
        'reconciled_tran'   => 'Amaran: Anda tidak dibenarkan menukar/memadam transaksi kerana ia telah disesuaikan!',
        'reconciled_doc'    => 'Amaran: Anda tidak dibenarkan menukar/memadam :type kerana ia mempunyai transaksi yang telah disesuaikan!',
        'disable_code'      => 'Amaran: Anda tidak dibenarkan menonaktifkan atau menukar mata wang <b>:name</b> kerana ia mempunyai :text yang berkaitan.',
        'payment_cancel'    => 'Amaran: Anda telah membatalkan bayaran :method terkini!',
        'missing_transfer'  => 'Amaran: Pemindahan yang berkaitan dengan transaksi ini tiada. Anda patut mempertimbangkan untuk memadam transaksi ini.',
        'connect_tax'       => 'Amaran: :type ini mempunyai jumlah cukai. Cukai yang ditambah kepada :type tidak boleh disambung, jadi cukai akan ditambah kepada jumlah keseluruhan dan dikira dengan sewajarnya.',
        'contact_change'    => 'Amaran: Anda tidak dibenarkan menukar kontak pada :type yang telah dihantar, diterima, atau dibayar!',
    ],

];
