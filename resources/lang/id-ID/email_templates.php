<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Faktur {invoice_number} dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah menyiapkan faktur berikut untuk Anda: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian faktur dan melanjutkan dengan pembayaran dari tautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan ragu untuk menghubungi kami jika ada pertanyaan.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Pemberitahuan jatuh tempo faktur {invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Ini adalah pemberitahuan jatuh tempo untuk faktur <strong>{invoice_number}</strong>.<br /><br />Total faktur adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihat rincian faktur dan melanjutkan dengan pembayaran dari tautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Pemberitahuan jatuh tempo faktur {invoice_number}',
        'body'          => 'Halo,<br /><br />{customer_name} telah menerima pemberitahuan jatuh tempo untuk faktur <strong>{invoice_number}</strong>.<br /><br />Total faktur adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihat rincian faktur dari tautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Faktur berulang {invoice_number} dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Berdasarkan siklus berulang Anda, kami telah menyiapkan faktur berikut untuk Anda: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian faktur dan melanjutkan dengan pembayaran dari tautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan ragu untuk menghubungi kami jika ada pertanyaan.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Faktur berulang {invoice_number} dibuat',
        'body'          => 'Halo,<br /><br />Berdasarkan siklus berulang {customer_name}, faktur <strong>{invoice_number}</strong> telah dibuat secara otomatis.<br /><br />Anda dapat melihat rincian faktur dari tautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Faktur {invoice_number} telah dilihat',
        'body'          => 'Halo,<br /><br />{customer_name} telah melihat faktur <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian faktur dari tautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Tanda terima untuk faktur {invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Terima kasih atas pembayarannya. Temukan detail pembayaran di bawah ini:<br /><br />-------------------------------------------------<br />Jumlah: <strong>{transaction_total}</strong><br />Tanggal: <strong>{transaction_paid_date}</strong><br />Nomor Faktur: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Anda selalu dapat melihat rincian faktur dari tautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan ragu untuk menghubungi kami jika ada pertanyaan.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pembayaran diterima untuk faktur {invoice_number}',
        'body'          => 'Halo,<br /><br />{customer_name} telah mencatat pembayaran untuk faktur <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian faktur dari tautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Pemberitahuan pengingat tagihan {bill_number}',
        'body'          => 'Halo,<br /><br />Ini adalah pemberitahuan pengingat untuk tagihan <strong>{bill_number}</strong> kepada {vendor_name}.<br /><br />Total tagihan adalah {bill_total} dan jatuh tempo pada <strong>{bill_due_date}</strong>.<br /><br />Anda dapat melihat rincian tagihan dari tautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Tagihan berulang {bill_number} dibuat',
        'body'          => 'Halo,<br /><br />Berdasarkan siklus berulang {vendor_name}, tagihan <strong>{bill_number}</strong> telah dibuat secara otomatis.<br /><br />Anda dapat melihat rincian tagihan dari tautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Tanda terima Anda dari {company_name}',
        'body'          => 'Yang Terhormat {contact_name},<br /><br />Terima kasih atas pembayarannya. <br /><br />Anda dapat melihat rincian pembayaran di tautan berikut: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Jangan ragu untuk menghubungi kami jika ada pertanyaan.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Pembayaran dilakukan oleh {company_name}',
        'body'          => 'Yang Terhormat {contact_name},<br /><br />Kami telah melakukan pembayaran berikut. <br /><br />Anda dapat melihat rincian pembayaran di tautan berikut: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Jangan ragu untuk menghubungi kami jika ada pertanyaan.<br /><br />Hormat Kami,<br />{company_name}',
    ],
];
