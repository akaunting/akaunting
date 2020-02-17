<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'تم إنشاء الفاتورة {invoice_number}',
        'body'          => 'عزيزي {customer_name}،<br /><br /> قمنا باصدار الفاتورة التالية:<strong>{invoice_number}</strong>.<br /><br /> يمكنك اﻹطلاع على تفاصيل الفاتورة و اكمال الدفع عن طريق الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لاتتردد بالتواصل معنا لأي سؤال.<br /><br />تحيّاتي،<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'إشعار بتأخر فاتورة رقم {invoice_number}',
        'body'          => 'عزيزي {customer_name}،<br /><br /> هذا اشعار بتاخر فاتورة رقم <strong>{invoice_number}</strong>.<br /><br /> مجموع الفاتورة هو {invoice_total} و تاريخ الاستحقاق <strong>{invoice_due_date}</strong>.<br /><br /> يمكنك مشاهدة تفاصيل الفاتورة و اكمال الدفع عن طريق الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />تقبل تحيّاتي،<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'إشعار بتأخر فاتورة رقم {invoice_number}',
        'body'          => 'مرحبا {customer_name}،<br /><br /> تلقى اشعار بتاخر فاتورة رقم <strong>{invoice_number}</strong>.<br /><br /> مجموع الفاتورة هو {invoice_total} و تاريخ الاستحقاق <strong>{invoice_due_date}</strong>.<br /><br /> يمكنك مشاهدة تفاصيل الفاتورة و اكمال الدفع عن طريق الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />تقبل تحيّاتي،<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'تم إنشاء فاتورة متكررة',
        'body'          => 'عزيزي {customer_name}،<br /><br /> قمنا باصدار الفاتورة التالية:<strong>{invoice_number}</strong>.<br /><br /> يمكنك اﻹطلاع على تفاصيل الفاتورة و اكمال الدفع عن طريق الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لاتتردد بالتواصل معنا لأي سؤال.<br /><br />تحيّاتي،<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} تم إنشاء فاتورة متكررة',
        'body'          => 'مرحبا,<br /><br /> استنادا الى الدائرة المتكررة ل {customer_name},<strong>{invoice_number}</strong> تم إنشاء الفاتورة أتوماتكيا <br /><br /> يمكنك الاطلاع على تفاصيل الفاتورة من الرابط:<a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات{br /> {company_name>',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'تم استلام الدفعة لفاتورة رقم {invoice_number}',
        'body'          => 'عزيزي {customer_name}،<br /><br />شكرا للسداد، تجد تفاصيل الدفع في اﻷسفل:<br /><br />-------------------------------------------------<br /><br />القيمة: <strong>{transaction_total}<br /></strong> التاريخ: <strong>{transaction_paid_date}</strong><br /> رقم الفاتورة: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br /> يمكنك بالتاكيد اﻹطلاع على تفاصيل الفاتورة من خلال الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لاتتردد بالتواصل معنا لأي سؤال.<br /><br />تحيّاتي،<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'تم استلام الدفعة لفاتورة رقم {invoice_number}',
        'body'          => 'مرحبا، <br /><br />{customer_name} قام بتسديد فاتورة <strong>{invoice_number}</strong>.<br /><br />يمكنك اﻹطلاع على تفاصيل الفاتورة من خلال الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />تحيّاتي،<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} إشعار للتذكير بالفاتورة',
        'body'          => 'مرحبا {customer_name}،<br /><br /> هذا إشعار بتاخر فاتورة رقم <strong>{invoice_number}</strong>.<br /><br /> مجموع الفاتورة هو {invoice_total} و تاريخ الاستحقاق <strong>{invoice_due_date}</strong>.<br /><br /> يمكنك مشاهدة تفاصيل الفاتورة و اكمال الدفع عن طريق الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />تقبل تحيّاتي،<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} تم إنشاء فاتورة متكررة',
        'body'          => 'مرحبا,<br /><br /> استنادا الى الدائرة المتكررة ل <vendor_name} <strong>{bill_number}</strong} تم إنشاء الفاتورة أتوماتكيا <br /><br /> يمكنك الاطلاع على تفاصيل الفاتورة من الرابط: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br /< Regards,<br />{company_name} مع أطيب التحيات{br /> {company_name>',
    ],

];
