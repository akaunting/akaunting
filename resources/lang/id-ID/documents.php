<?php

return [

    'edit_columns'              => 'Sunting Kolom',
    'empty_items'               => 'Anda belum menambahkan item apa pun',
    'grand_total'               => 'Total Keseluruhan',
    'accept_payment_online'     => 'Menerima Pembayaran Daring',
    'transaction'               => 'Pembayaran untuk :amount telah dilakukan menggunakan :account',
    'portal_transaction'        => 'Pembayaran untuk :amount telah dilakukan menggunakan :payment_method',
    'billing'                   => 'Tagihan',
    'advanced'                  => 'Lanjutan',

    'item_price_hidden'         => 'Kolom ini disembunyikan pada :type Anda.',

    'actions' => [
        'cancel'                => 'Batal',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Anda</b> menandai faktur ini sebagai',
        'services'              => 'Layanan',
        'another_item'          => 'Item Lain',
        'another_description'   => 'dan deskripsi lainnya',
        'more_item'             => '+:count item lagi',
    ],

    'statuses' => [
        'draft'                 => 'Konsep',
        'sent'                  => 'Terkirim',
        'expired'               => 'Kadaluarsa',
        'viewed'                => 'Dilihat',
        'approved'              => 'Disetujui',
        'received'              => 'Diterima',
        'refused'               => 'Ditolak',
        'restored'              => 'Dipulihkan',
        'reversed'              => 'Terbalik',
        'partial'               => 'Sebagian',
        'paid'                  => 'Dibayar',
        'pending'               => 'Tertunda',
        'invoiced'              => 'Tertagih',
        'overdue'               => 'Jatuh Tempo',
        'unpaid'                => 'Belum Dibayar',
        'cancelled'             => 'Dibatalkan',
        'voided'                => 'Membatalkan',
        'completed'             => 'Selesai',
        'shipped'               => 'Dikirim',
        'refunded'              => 'Dikembalikan',
        'failed'                => 'Gagal',
        'denied'                => 'Ditolak',
        'processed'             => 'Diproses',
        'open'                  => 'Terbuka',
        'closed'                => 'Ditutup ',
        'billed'                => 'Ditagih',
        'delivered'             => 'Terkirim',
        'returned'              => 'Dikembalikan',
        'drawn'                 => 'Ditarik',
        'not_billed'            => 'Belum Ditagih',
        'issued'                => 'Diterbitkan',
        'not_invoiced'          => 'Belum Ditagih',
        'confirmed'             => 'Dikonfirmasi',
        'not_confirmed'         => 'Belum Dikonfirmasi',
        'active'                => 'Aktif',
        'ended'                 => 'Selesai',
    ],

    'form_description' => [
        'companies'             => 'Ubah alamat, logo, dan informasi lain perusahaan Anda.',
        'billing'               => 'Detail tagihan muncul di dokumen Anda.',
        'advanced'              => 'Pilih kategori, tambahkan, atau sunting footer, dan tambahkan lampiran ke :type.',
        'attachment'            => 'Unduh berkas terlampir ke :type',
    ],

    'slider' => [
        'create'            => ':user membuat :type ini pada :date',
        'create_recurring'  => ':user membuat templat berulang ini pada :date',
        'send'              => ':user mengirim :type ini pada :date',
        'schedule'          => 'Ulangi setiap :interval :frequency sejak :date',
        'children'          => ':count :type dibuat secara otomatis',
        'cancel'            => ':user membatalkan :type ini pada :date',
    ],

    'messages' => [
        'email_sent'            => ':type email telah dikirim!',
        'restored'              => ':type telah dipulihkan!',
        'marked_as'             => ':type ditandai sebagai :status!',
        'marked_sent'           => ':type ditandai terkirim!',
        'marked_paid'           => ':type ditandai telah terbayar!',
        'marked_viewed'         => ':type ditandai telah dilihat!',
        'marked_cancelled'      => ':type ditandai telah dibatalkan!',
        'marked_received'       => ':type ditandai telah diterima!',
    ],

    'recurring' => [
        'auto_generated'        => 'Dihasilkan secara otomatis',

        'tooltip' => [
            'document_date'     => 'Tanggal :type akan ditetapkan secara otomatis berdasarkan jadwal dan frekuensi :type.',
            'document_number'   => 'Nomor :type akan ditetapkan secara otomatis ketika setiap :type berulang dihasilkan.',
        ],
    ],

    'empty_attachments'         => 'Tidak ada berkas yang dilampirkan pada :type ini.',
];
