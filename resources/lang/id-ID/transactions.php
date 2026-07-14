<?php

return [

    'payment_received'      => 'Pembayaran Diterima',
    'payment_made'          => 'Pembayaran Dilakukan',
    'paid_by'               => 'Dibayar Oleh',
    'paid_to'               => 'Dibayarkan Ke',
    'related_invoice'       => 'Faktur Terkait',
    'related_bill'          => 'Tagihan Terkait',
    'recurring_income'      => 'Penghasilan Berulang',
    'recurring_expense'     => 'Biaya Berulang',
    'included_tax'          => 'Jumlah pajak yang termasuk',
    'connected'             => 'Terhubung',
    'connect_message'       => 'Pajak untuk :type ini tidak dihitung selama proses koneksi. Pajak tidak dapat dihubungkan.',

    'form_description' => [
        'general'           => 'Di sini Anda dapat memasukkan informasi umum transaksi seperti tanggal, jumlah, akun, deskripsi, dll.',
        'assign_income'     => 'Pilih kategori dan pelanggan untuk membuat laporan Anda lebih detail.',
        'assign_expense'    => 'Pilih kategori dan pemasok untuk membuat laporan Anda lebih detail.',
        'other'             => 'Masukkan nomor dan referensi untuk menyimpan transaksi yang ditautkan ke catatan Anda.',
    ],

    'slider' => [
        'create'            => ':user membuat transaksi ini pada :date',
        'attachments'       => 'Unduh berkas yang dilampirkan pada transaksi ini',
        'create_recurring'  => ':user membuat template berulang ini pada :date',
        'schedule'          => 'Ulangi setiap :interval :frequency sejak :date',
        'children'          => ':count transaksi dibuat secara otomatis',
        'connect'           => 'Transaksi ini terhubung ke :count transaksi',
        'transfer_headline' => '<div> <span class="font-bold"> Dari: </span> :from_account </div> <div> <span class="font-bold"> ke: </span> :to_account </div>',
        'transfer_desc'     => 'Transfer dibuat pada :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Pelanggan Anda dapat melihat transaksi di tautan ini',
            'copy_link'     => 'Salin tautan dan bagikan dengan pelanggan Anda.',
        ],

        'expense' => [
            'show_link'     => 'Pemasok Anda dapat melihat transaksi pada tautan ini',
            'copy_link'     => 'Salin tautan dan bagikan dengan pemasok.',
        ],
    ],

    'sticky' => [
        'description'       => 'Anda melihat pratinjau bagaimana pelanggan Anda akan melihat versi web pembayaran Anda.',
    ],

    'messages' => [
        'update_document_transaction' => 'Anda dapat memperbarui transaksi ini. Anda harus pergi ke dokumen dan menyuntingnya di sana.',
        'create_document_transaction_error' => 'Titik akhir ini tidak dapat ditambahkan ke dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Titik akhir ini tidak dapat diperbarui ke dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Titik akhir ini tidak dapat dihapus dari dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ],

];
