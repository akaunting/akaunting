<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} faktura yaradıldı',
        'body'          => 'Hörmətli {customer_name},<br /><br /><strong>{invoice_number}</strong> nömrəli fakturanız hazırlandı.<br /><br />Aşağıdakı linkə daxil olaraq faktura haqqında ətraflı məlumat əldə edə və online ödəniş edə bilərsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hər hansı bir problemlə üzləşdikdə zəhmət olmazsa bizə yazın.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} fakturası üçün gecikən ödəmə xatırlatması',
        'body'          => 'Hörmətli {customer_name},<br /><br /><strong>{invoice_number}</strong> nömrəli fkatura üçün ödənişiniz gecikdi.<br /><br />Qeyd edilən faktura üçün {invoice_total} məbləğində vəsait ən son <strong>{invoice_due_date}</strong> tarixində ödənilməlidir.<br /><br />Aşağıdakı linkə daxil olaraq faktura haqqında ətraflı məlumat əldə edə və online ödəniş edə bilərsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} fakturanın ödənişi gecikib',
        'body'          => 'Salam,<br /><br />{customer_name} müştərinizə <strong>{invoice_number}</strong> fakturası üçün gecikmiş ödəniş xəbərdarlığı göndərildi.<br /><br />Faktura məbləği {invoice_total} və son ödənişi <strong>{invoice_due_date}</strong> tarixində həyata keçirməli idi.<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} təkrarlanan faktura yaradıldı',
        'body'          => 'Hörmətli {customer_name},<br /><br />Ödəniş dövrünə uyğun olaraq <strong>{invoice_number}</strong> nömrəli fakturanız hazırlandı.<br /><br />Aşağıdakı linkə daxil olaraq faktura haqqında ətraflı məlumat əldə edə və online ödəniş edə bilərsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hər hansı bir problemlə üzləşdikdə zəhmət olmazsa bizə yazın.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} təkrarlanan faktura yaradıldı',
        'body'          => 'Salam,<br /><br />{customer_name} müştərinizə ödəmə dövrünə uyğun olaraq <strong>{invoice_number}</strong> nömrəli faktura avtomatik olaraq yaradıldı.<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '{invoice_number} faturasının ödemesi alındı',
        'body'          => 'Hörmətli {customer_name},<br /><br />Ödənişiniz üçün təşəkkür edirik. Ödənişiniz haqqında ətraflı məlumat:<br /><br />-------------------------------------------------<br /><br />Məbləğ: <strong>{transaction_total}<br /></strong>Tarix: <strong>{transaction_paid_date}</strong><br />Faktura nömrəsi: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Hər hansı bir problemlə üzləşdikdə zəhmət olmazsa bizə yazın.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '{invoice_number} faktura üçün ödəniş edildi',
        'body'          => 'Salam,<br /><br />{customer_name} mütəriniz <strong>{invoice_number}</strong> nömrəli faktura üçün ödəniş etdi.<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} xərc fakturası üçün ödəniş satırlatması',
        'body'          => 'Salam,<br /><br /><strong>{vendor_name}</strong> tədarükçünüzdən <strong>{bill_number}</strong> nömrəli xərc fakturası üçün ödəniş xatırlatmasıdır.<br /><br />Fakturanın məbləği {bill_total} və son ödəniş <strong>{bill_due_date}</strong> tarixində edilməlidir.<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} təkrarlanan xərc fakturası yaradıldı',
        'body'          => 'Salam,<br /><br />{vendor_name} tədarükçünüzün ödəniş dövrünə uyğun olaraq <strong>{bill_number}</strong> nömrəli xərc fakturası avtomatik olaraq yaradıldı.<br /><br />Aşağıdakı linkdən faktura haqqında ətraflı məlumat əldə edə bilərsiniz: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />İşlərinizdə uğurlar,<br />{company_name}',
    ],

];
