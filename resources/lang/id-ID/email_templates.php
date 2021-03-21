<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} tagihan dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah mempersiapkan tagihan kepada Anda sebagai berikut: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian tagihan dan dilanjutkan dengan pembayaran dari link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Apabila ada pertanyaan harap hubungi kami.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'pemberitahuan jatuh tempo tagihan nomor{invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Ini adalah pemberitahuan jatuh tempo untuk tagihan nomor <strong>{invoice_number}</strong> invoice.<br /><br />Total tagihannya adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihat rincian tagihan dan melanjutkannya dengan pembayaran dengan link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'pemberitahuan jatuh tempo tagihan nomor{invoice_number}',
        'body'          => 'Hello,<br /><br />{customer_name} telah menerima pemberitahuan jatuh tempo untuk tagihan nomor <strong>{invoice_number}</strong> <br /><br />Total tagihannya adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihan rincian tagihan dari link berikut ini: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'tagihan ulang nomor {invoice_number} dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah mempersiapkan tagihan kepada Anda sebagai berikut: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian tagihan dan dilanjutkan dengan pembayaran dari link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Apabila ada pertanyaan harap hubungi kami.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'tagihan ulang nomor {invoice_number} dibuat',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pembayaran diterima untuk faktur {invoice_number}',
        'body'          => 'Dear {customer_name}, <br /> <br /> Terima kasih atas pembayarannya. Temukan detail pembayaran di bawah ini: <br /> <br /> ------------------------------------ ------------- <br /> Jumlah: <strong> {transaction_total} </strong> <br /> Tanggal: <strong> {transaction_paid_date} </strong> <br /> Faktur Nomor: <strong> {invoice_number} </strong> <br /> ---------------------------------- --------------- <br /> <br /> Anda selalu dapat melihat detail faktur dari tautan berikut: <a href="{invoice_guest_link}"> {invoice_number} </ a>. <br /> <br /> Jangan ragu untuk menghubungi kami jika ada pertanyaan. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pembayaran diterima untuk faktur {invoice_number}',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} pemberitahuan pengingat tagihan',
        'body'          => 'Halo, <br /> <br /> Ini adalah pemberitahuan untuk tagihan <strong> {bill_number} </strong> ke {vendor_name}. <br /> <br /> Total tagihan adalah {bill_total} dan sudah jatuh tempo <strong> {bill_due_date} </strong>. <br /> <br /> Anda dapat melihat detail tagihan dari tautan berikut: <a href="{bill_admin_link}"> {bill_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} tagihan berulang dibuat',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

];
