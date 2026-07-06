<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} Invois dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah Menyiapkan Bil kepada Anda sebagai berikut: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian Bil dan dilanjutkan dengan bayaran dari link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hubungi Kami Jika ada pertanyaan.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'pemberitahuan jatuh tempo Bil nomor{invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Ini adalah pemberitahuan jatuh tempo untuk Bil nomor <strong>{invoice_number}</strong> invoice.<br /><br />Total Bilnya adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihat rincian Bil dan melanjutkannya dengan bayaran dengan link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'pemberitahuan jatuh tempo Bil nomor{invoice_number}',
        'body'          => 'Hello,<br /><br />{customer_name} telah menerima pemberitahuan jatuh tempo untuk Bil nomor <strong>{invoice_number}</strong> <br /><br />Total Bilnya adalah {invoice_total} dan jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda dapat melihan rincian Bil dari link berikut ini: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Bil ulang nomor {invoice_number} dibuat',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah mempersiapkan Bil kepada Anda sebagai berikut: <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat rincian Bil dan dilanjutkan dengan bayaran dari link berikut ini: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Apabila ada pertanyaan harap hubungi kami.<br /><br />Hormat Kami,,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Bil ulang nomor {invoice_number} dibuat',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'faktur {invoice_number} telah dilihat',
        'body'          => 'Halo,<br /><br />{customer_name} telah melihat faktur <strong>{invoice_number}</strong>.<br /><br />Anda dapat melihat detail faktur di tautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salam,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Bayaran diterima untuk faktur {invoice_number}',
        'body'          => 'Dear {customer_name}, <br /> <br /> Terima kasih atas bayarannya. Temukan detail bayaran di bawah ini: <br /> <br /> ------------------------------------ ------------- <br /> Jumlah: <strong> {transaction_total} </strong> <br /> Tanggal: <strong> {transaction_paid_date} </strong> <br /> Invois Nomor: <strong> {invoice_number} </strong> <br /> ---------------------------------- --------------- <br /> <br /> Anda selalu dapat melihat detail faktur dari tautan berikut: <a href="{invoice_guest_link}"> {invoice_number} </ a>. <br /> <br /> Jangan ragu untuk menghubungi kami jika ada pertanyaan. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Bayaran diterima untuk faktur {invoice_number}',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} pemberitahuan pengingat Bil',
        'body'          => 'Halo, <br /> <br /> Ini adalah pemberitahuan untuk Bil <strong> {bill_number} </strong> ke {vendor_name}. <br /> <br /> Total Bil adalah {bill_total} dan sudah jatuh tempo <strong> {bill_due_date} </strong>. <br /> <br /> Anda dapat melihat detail Bil dari tautan berikut: <a href="{bill_admin_link}"> {bill_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} Bil berulang dibuat',
        'body'          => 'Halo, <br /> <br /> Berdasarkan lingkaran berulang {customer_name}, <strong> {invoice_number} </strong> faktur telah dibuat secara otomatis. <br /> <br /> Anda dapat melihat detail faktur dari tautan berikut: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Salam, <br /> {company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Tanda terima Anda dari {company_name}',
        'body'          => 'Yang Terhormat {contact_name},<br /><br />Terima kasih atas bayarannya. <br /><br />Anda dapat melihat rincian bayaran di tautan berikut ini: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Silakan hubungi kami jika ada pertanyaan lebih lanjut.<br /><br />Hormat Kami,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Bayaran dilakukan oleh {company_name}',
        'body'          => 'Yang Terhormat {contact_name},<br /><br />Kami telah melakukan bayaran sebagai berikut. <br /><br />Anda dapat melihat rincian bayaran di tautan berikut ini: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Silakan hubungi kami jika ada pertanyaan lebih lanjut.<br /><br />Hormat Kami,<br />{company_name}',
    ],
];
