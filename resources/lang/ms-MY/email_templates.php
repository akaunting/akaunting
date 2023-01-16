<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} Invois telah dihasilkan',
        'body'          => 'Kepada {customer_name},<br /><br />Kami telah menyediakan invois ini kepada anda: <strong>{invoice_number}</strong>.<br /><br />Anda boleh merujuk kepada butiran invois dan teruskan dengan pembayaran melalui pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Untuk sebarang pertanyaan, sila berhubung terus dengan pihak kami.<br /><br />Yang benar,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} Notis invois tertunggak',
        'body'          => 'Kepada {customer_name},<br /><br />Ini adalah notis pembayaran tertunggak untuk <strong>{invoice_number}</strong> invois.<br /><br />Jumlah invois tersebut adalah {invoice_total} dan tarikh akhir bayaran <strong>{invoice_due_date}</strong>.<br /><br /> Anda boleh merujuk kepada butiran invois dan teruskan dengan pembayaran melalui pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Best Yang benar,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} Notis invois tertunggak',
        'body'          => 'Hai,<br /><br />{customer_name} telah menerima peringatan notis pembayaran tertunggak dari <strong>{invoice_number}</strong> invois.<br /><br /> Jumlah invois tersebut adalah {invoice_total} dan tarikh akhir bayaran <strong>{invoice_due_date}</strong>.<br /><br /> Anda boleh merujuk kepada butiran invois melalui pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Yang benar,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} Invois ulangan telah dihasilkan',
        'body'          => 'Kepada {customer_name},<br /><br />Berdasarkan kitaran ulangan anda, kami telah menyediakan invois berikut: <strong>{invoice_number}</strong>.<br /><br /> Anda boleh merujuk kepada butiran invois dan teruskan dengan pembayaran melalui pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> Untuk sebarang pertanyaan, sila berhubung terus dengan pihak kami.<br /><br />Best Yang benar,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} Invois ulangan telah dihasilkan',
        'body'          => 'Hai,<br /><br />Berdasarkan dari {customer_name} kitaran ulangan, <strong>{invoice_number}</strong> invois telah dihasilkan secara automatik <br /><br /> Anda boleh merujuk kepada butiran invois melalui pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Yang benar,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pembayaran untuk invois {invoice_number} telah diterima',
        'body'          => 'Kepada {customer_name},<br /><br />Terima kasih untuk pembayaran anda. Dibawah adalah butiran pembayaran anda:<br /><br />-------------------------------------------------<br />Amount: <strong>{transaction_total}</strong><br />Tarikh: <strong>{transaction_paid_date}</strong><br />Nombor invois: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br /> Anda boleh senantiasa melihat butiran invois melalui pautan berikut: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> Untuk sebarang pertanyaan, sila berhubung terus dengan pihak kami.<br /><br />Yang benar,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pembayaran untuk invois {invoice_number} telah diterima',
        'body'          => 'Hai,<br /><br />{customer_name} pembayaran telah direkodkan untuk <strong>{invoice_number}</strong> invois.<br /><br /> Anda boleh merujuk kepada butiran invois melalui pautan berikut: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Yang benar,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} Notis peringatan bil',
        'body'          => 'Hai,<br /><br />Ini adalah notis peringatan untuk <strong>{bill_number}</strong> di bilkan kepada {vendor_name}.<br /><br />Bil berjumlah {bill_total} dan Tarikh akhir bayaran <strong>{bill_due_date}</strong>.<br /><br /> Anda boleh merujuk kepada butiran invois melalui pautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Yang benar,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} Bil ulangan telah dihasilkan',
        'body'          => 'Hai,<br /><br />Berdasarkan dari {vendor_name} kitaran ulangan, <strong>{bill_number}</strong> invois telah dihasilkan secara automatik.<br /><br /> Anda boleh merujuk kepada butiran bil melalui pautan berikut: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Yang benar,<br />{company_name}',
    ],

];
