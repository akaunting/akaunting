<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fatura oluşturuldu',
        'body'          => 'Sayın {customer_name},<br /><br /><strong>{invoice_number}</strong> numarası ile faturanızı hazırladık.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarını görüntüleyip ödemesini online olarak kredi/banka kartınızla yapabilirsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Herhangi bir sorunuz olursa lütfen bize yazın.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} faturası için gecikmiş ödeme hatırlatması',
        'body'          => 'Sayın {customer_name},<br /><br /><strong>{invoice_number}</strong> numaralı fatura için ödemeniz gecikti.<br /><br />Faturanın tutarı {invoice_total} ve son ödemesi <strong>{invoice_due_date}</strong> tarihinde yapılması gerekiyordu.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarını görüntüleyip ödemesini online olarak kredi/banka kartınızla yapabilirsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} faturasının ödemesi gecikmiş',
        'body'          => 'Merhaba,<br /><br />{customer_name} müşterinize <strong>{invoice_number}</strong> faturası için gecikmiş ödeme uyarısı gönderildi.<br /><br />Faturanın tutarı {invoice_total} ve son ödemesi <strong>{invoice_due_date}</strong> tarihinde yapılması gerekiyordu.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} tekrarlı fatura oluşturuldu',
        'body'          => 'Sayın {customer_name},<br /><br />Ödeme döneminize uygun olarak <strong>{invoice_number}</strong> numarası ile faturanızı hazırladık.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarını görüntüleyip ödemesini online olarak kredi/banka kartınızla yapabilirsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Herhangi bir sorunuz olursa lütfen bize yazın.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} tekrarlı fatura oluşturuldu',
        'body'          => 'Merhaba,<br /><br />{customer_name} müşterinizin ödeme dönemine uygun olarak <strong>{invoice_number}</strong> numaralı fatura otomatik olarak oluşturuldu.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '{invoice_number} faturasının ödemesi alındı',
        'body'          => 'Sayın {customer_name},<br /><br />Ödeme için teşekkür ederiz. Yaptığınız ödemenin detayları:<br /><br />-------------------------------------------------<br /><br />Tutar: <strong>{transaction_total}<br /></strong>Tarih: <strong>{transaction_paid_date}</strong><br />Fatura Numarası: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Herhangi bir sorunuz olursa lütfen bize yazın.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '{invoice_number} faturasının ödemesi yapıldı',
        'body'          => 'Merhaba,<br /><br />{customer_name} müşteriniz <strong>{invoice_number}</strong> numaralı fatura için ödeme yaptı.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} gider faturası için ödeme hatırlatması',
        'body'          => 'Merhaba,<br /><br /><strong>{vendor_name}</strong> tedarikçinize <strong>{bill_number}</strong> numaralı gider faturası için ödeme hatırlatmasıdır.<br /><br />Faturanın tutarı {bill_total} ve son ödemesi <strong>{bill_due_date}</strong> tarhinde yapılması gerekir.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} tekrarlı gider faturası oluşturuldu',
        'body'          => 'Merhaba,<br /><br />{vendor_name} tedarikçinizin ödeme dönemine uygun olarak <strong>{bill_number}</strong> numaralı gider faturası otomatik olarak oluşturuldu.<br /><br />Aşağıdaki bağlantıdan faturanın detaylarına ulaşabilirsiniz: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Kolay gelsin,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} ödemesi oluşturuldu',
        'body'          => 'Sayın {customer_name},<br /><br />Aşağıdaki ödemeyi hazırladık. <br /><br />Ödeme ayrıntılarını şu bağlantıdan görebilirsiniz: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Bizimle iletişime geçmekten çekinmeyin. herhangi bir sorunuz varsa..<br /><br />Saygılarımızla,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => '{revenue_date} ödemesi oluşturuldu',
        'body'          => 'Sayın {vendor_name},<br /><br />Aşağıdaki ödemeyi hazırladık. <br /><br />Ödeme ayrıntılarını aşağıdaki bağlantıdan görebilirsiniz: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />Bizimle iletişime geçmekten çekinmeyin. herhangi bir sorunuz varsa..<br /><br />Saygılarımızla,<br />{company_name}',
    ],
];
