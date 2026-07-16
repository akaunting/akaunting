<?php

return [

    'payment_received'      => 'Bayaran Diterima',
    'payment_made'          => 'Bayaran Dibuat',
    'paid_by'               => 'Dibayar Oleh',
    'paid_to'               => 'Dibayar Kepada',
    'related_invoice'       => 'Invois Berkaitan',
    'related_bill'          => 'Bil Berkaitan',
    'recurring_income'      => 'Pendapatan Berulang',
    'recurring_expense'     => 'Perbelanjaan Berulang',
    'included_tax'          => 'Jumlah cukai termasuk',
    'connected'             => 'Disambung',
    'connect_message'       => 'Cukai untuk :type ini tidak dikira semasa proses sambungan. Cukai tidak boleh disambung.',

    'form_description' => [
        'general'           => 'Di sini anda boleh memasukkan maklumat umum transaksi seperti tarikh, jumlah, akaun, penerangan, dll.',
        'assign_income'     => 'Pilih kategori dan pelanggan untuk menjadikan laporan anda lebih terperinci.',
        'assign_expense'    => 'Pilih kategori dan pembekal untuk menjadikan laporan anda lebih terperinci.',
        'other'             => 'Masukkan nombor dan rujukan untuk memastikan transaksi terpaut dengan rekod anda.',
    ],

    'slider' => [
        'create'            => ':user membuat transaksi ini pada :date',
        'attachments'       => 'Unduh file yang dilampirkan pada transaksi ini',
        'create_recurring'  => ':user membuat template berulang ini pada :date',
        'schedule'          => 'Ulangi setiap :interval :frequency sejak :date',
        'children'          => ':count transaksi dibuat secara otomatis',
        'connect'           => 'Transaksi ini terhubung ke :count transaksi',
        'transfer_headline' => '<div> <span class="font-bold"> Dari: </span> :from_account </div> <div> <span class="font-bold"> ke: </span> :to_account </div>',
        'transfer_desc'     => 'Pemindahan dibuat pada :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Pelanggan Anda dapat melihat transaksi di tautan ini',
            'copy_link'     => 'Salin tautan dan bagikan dengan pelanggan Anda.',
        ],

        'expense' => [
            'show_link'     => 'Pembekal Anda dapat melihat transaksi di tautan ini',
            'copy_link'     => 'Salin tautan dan bagikan dengan pembekal Anda.',
        ],
    ],

    'sticky' => [
        'description'       => 'Anda sedang pratonton cara pelanggan anda akan melihat versi web bayaran anda.',
    ],

    'messages' => [
        'update_document_transaction' => 'Anda dapat memperbarui transaksi ini. Anda harus pergi ke dokumen dan menyuntingnya di sana.',
        'create_document_transaction_error' => 'Titik akhir ini tidak dapat ditambahkan ke dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Titik akhir ini tidak dapat diperbarui ke dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Titik akhir ini tidak dapat dihapus dari dokumen. Gunakan {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ],

];
