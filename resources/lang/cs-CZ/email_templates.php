<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Faktura {invoice_number} vytvořena',
        'body'          => 'Vážený(á) {customer_name},<br /><br />Připravili jsme vám následující fakturu: <strong>{invoice_number}</strong>.<br /><br />Detaily faktury a zaplacení faktury najdete na odkazu: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />V připadě dotazů nás neváhejte kontaktovat..<br /><br />S pozdravem,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} oznámení o zpoždění faktury',
        'body'          => 'Vážený(á) {customer_name},<br /><br />Toto je oznámení o prodlení <strong>{invoice_number}</strong> faktury.<br /><br />Suma faktury je {invoice_total} a je splatná k <strong>{invoice_due_date}</strong>.<br /><br />Detail faktury a provedení platby naleznete na následujícím odkazu: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />S pozdravem,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} upozornění na fakturu po splatnosti',
        'body'          => 'Dobrý den, <br /><br />{customer_name} obdržel oznámení o faktuře <strong>{invoice_number}</strong> v prodlení.<br /><br />Suma faktury je {invoice_total} a byla splatná k <strong>{invoice_due_date}</strong><br /><br />Detail faktury naleznete na následujícím odkazu: <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />S pozdravem,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} opakující se faktura byla vytvořena',
        'body'          => 'Vážený(á) {customer_name},<br /><br /> na základě Vašeho opakujícího se cyklu jsme Vám připravili následující fakturu: <strong>{invoice_number}</strong>.<br /><br />Detaily faktury a zaplacení faktury najdete na: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />V případě dotazů nás neváhejte kontaktovat..<br /><br />S pozdravem,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} opakující se faktura byla vytvořena',
        'body'          => 'Hello,<br /><br /> Based on {customer_name} recurring circle, <strong>{invoice_number}</strong> invoice has been automatically created.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Platba za fakturu č. {invoice_number} přijata ',
        'body'          => 'Vážený {customer_name},<br /><br />Děkujeme za platbu. Údaje o platbě:<br /><br />-------------------------------------------------<br /><br />Částka: <strong>{transaction_total}<br /></strong>Datum: <strong>{transaction_paid_date}</strong><br />faktura číslo: <strong>{invoice_number}<br /><br /></strong>-----------------------------------------------------------------<br /><br />Detaily faktury na následujícím odkazu: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neváhejte se na nás obrátit v případě dotazu.<br /><br />S pozdravem,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Platba za fakturu č. {invoice_number} přijata',
        'body'          => 'Dobrý den, <br /><br />{customer_name} zaznamenal(a) platbu faktury <strong>{invoice_number}</strong>.<br /><br /> Podrobnosti faktury naleznete na následujícím odkazu: <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />S pozdravem,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} oznámení o účtu v prodlevě',
        'body'          => 'Dobrý den,<br /><br />Toto je upomínka pro příkaz <strong>{bill_number}</strong> pro {vendor_name}.<br /><br />Suma příkazu je {bill_total} a je splatná k <strong>{bill_due_date}</strong><br /><br /> Podrobnosti o příkazu najdete na následujícím odkazu: <a href="{bill_admin_link}">{bill_number}</a><br /><br />S pozdravem,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} opakující se příkaz byl vytvořen',
        'body'          => 'Hello,<br /><br /> Based on {vendor_name} recurring circle, <strong>{bill_number}</strong> invoice has been automatically created.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

];
