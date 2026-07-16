<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} invois dicipta',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah menyediakan invois berikut untuk anda: <strong>{invoice_number}</strong>.<br /><br />Anda boleh melihat butiran invois dan meneruskan bayaran dari pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan teragak-agak untuk menghubungi kami jika ada sebarang soalan.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Notis kelewatan invois {invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Ini adalah notis kelewatan untuk invois <strong>{invoice_number}</strong>.<br /><br />Jumlah invois ialah {invoice_total} dan telah jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda boleh melihat butiran invois dan meneruskan bayaran dari pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Notis kelewatan invois {invoice_number}',
        'body'          => 'Halo,<br /><br />{customer_name} telah menerima notis kelewatan untuk invois <strong>{invoice_number}</strong>.<br /><br />Jumlah invois ialah {invoice_total} dan telah jatuh tempo pada <strong>{invoice_due_date}</strong>.<br /><br />Anda boleh melihat butiran invois dari pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Invois berulang {invoice_number} dicipta',
        'body'          => 'Kepada {customer_name},<br /><br />Berdasarkan kitaran berulang anda, kami telah menyediakan invois berikut untuk anda: <strong>{invoice_number}</strong>.<br /><br />Anda boleh melihat butiran invois dan meneruskan bayaran dari pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan teragak-agak untuk menghubungi kami jika ada sebarang soalan.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Invois berulang {invoice_number} dicipta',
        'body'          => 'Halo,<br /><br />Berdasarkan kitaran berulang {customer_name}, invois <strong>{invoice_number}</strong> telah dicipta secara automatik.<br /><br />Anda boleh melihat butiran invois dari pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Invois {invoice_number} telah dilihat',
        'body'          => 'Halo,<br /><br />{customer_name} telah melihat invois <strong>{invoice_number}</strong>.<br /><br />Anda boleh melihat butiran invois dari pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Resit anda untuk invois {invoice_number}',
        'body'          => 'Kepada {customer_name},<br /><br />Terima kasih atas bayaran. Sila lihat butiran bayaran di bawah:<br /><br />-------------------------------------------------<br />Jumlah: <strong>{transaction_total}</strong><br />Tarikh: <strong>{transaction_paid_date}</strong><br />Nombor Invois: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Anda sentiasa boleh melihat butiran invois dari pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Jangan teragak-agak untuk menghubungi kami jika ada sebarang soalan.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Bayaran diterima untuk invois {invoice_number}',
        'body'          => 'Halo,<br /><br />{customer_name} telah merekodkan bayaran untuk invois <strong>{invoice_number}</strong>.<br /><br />Anda boleh melihat butiran invois dari pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Notis peringatan bil {bill_number}',
        'body'          => 'Halo,<br /><br />Ini adalah notis peringatan untuk bil <strong>{bill_number}</strong> kepada {vendor_name}.<br /><br />Jumlah bil ialah {bill_total} dan akan jatuh tempo pada <strong>{bill_due_date}</strong>.<br /><br />Anda boleh melihat butiran bil dari pautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Bil berulang {bill_number} dicipta',
        'body'          => 'Halo,<br /><br />Berdasarkan kitaran berulang {vendor_name}, bil <strong>{bill_number}</strong> telah dicipta secara automatik.<br /><br />Anda boleh melihat butiran bil dari pautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Resit anda dari {company_name}',
        'body'          => 'Kepada {contact_name},<br /><br />Terima kasih atas bayaran. <br /><br />Anda boleh melihat butiran bayaran dari pautan berikut: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Jangan teragak-agak untuk menghubungi kami jika ada sebarang soalan.<br /><br />Salam hormat,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Bayaran dibuat oleh {company_name}',
        'body'          => 'Kepada {contact_name},<br /><br />Kami telah membuat bayaran berikut. <br /><br />Anda boleh melihat butiran bayaran dari pautan berikut: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Jangan teragak-agak untuk menghubungi kami jika ada sebarang soalan.<br /><br />Salam hormat,<br />{company_name}',
    ],
];
