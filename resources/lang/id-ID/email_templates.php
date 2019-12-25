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
        'body'          => 'Dear {customer_name},<br /><br />Based on your recurring circle, we have prepared the following invoice for you: <strong>{invoice_number}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} recurring invoice created',
        'body'          => 'Hello,<br /><br /> Based on {customer_name} recurring circle, <strong>{invoice_number}</strong> invoice has been automatically created.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Payment received for {invoice_number} invoice',
        'body'          => 'Dear {customer_name},<br /><br />Thank you for the payment. Find the payment details below:<br /><br />-------------------------------------------------<br /><br />Amount: <strong>{transaction_total}<br /></strong>Date: <strong>{transaction_paid_date}</strong><br />Invoice Number: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />You can always see the invoice details from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Payment received for {invoice_number} invoice',
        'body'          => 'Hello,<br /><br />{customer_name} recorded a payment for <strong>{invoice_number}</strong> invoice.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} bill reminding notice',
        'body'          => 'Hello,<br /><br />This is a reminding notice for <strong>{bill_number}</strong> bill to {vendor_name}.<br /><br />The bill total is {bill_total} and is due <strong>{bill_due_date}</strong>.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} recurring bill created',
        'body'          => 'Hello,<br /><br /> Based on {vendor_name} recurring circle, <strong>{bill_number}</strong> invoice has been automatically created.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

];
