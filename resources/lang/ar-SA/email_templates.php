<?php

return [

    'invoice_new_customer' => [
        'subject' => 'تم إنشاء الفاتورة {invoice_number}',
        'body'    => 'مرحباً {customer_name}،<br /><br />أعددنا لك الفاتورة التالية: <strong>{invoice_number}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة وإتمام الدفع عبر الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لا تتردد في التواصل معنا إذا كانت لديك أي أسئلة.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject' => 'إشعار بتأخر الفاتورة {invoice_number}',
        'body'    => 'مرحباً {customer_name}،<br /><br />هذا إشعار بتأخر سداد الفاتورة <strong>{invoice_number}</strong>.<br /><br />إجمالي الفاتورة هو {invoice_total}، وكان تاريخ استحقاقها <strong>{invoice_due_date}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة وإتمام الدفع عبر الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject' => 'إشعار بتأخر الفاتورة {invoice_number}',
        'body'    => 'مرحباً،<br /><br />تلقى {customer_name} إشعاراً بتأخر سداد الفاتورة <strong>{invoice_number}</strong>.<br /><br />إجمالي الفاتورة هو {invoice_total}، وكان تاريخ استحقاقها <strong>{invoice_due_date}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة عبر الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject' => 'تم إنشاء الفاتورة المتكررة {invoice_number}',
        'body'    => 'مرحباً {customer_name}،<br /><br />بناءً على دورة التكرار المحددة، أعددنا لك الفاتورة التالية: <strong>{invoice_number}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة وإتمام الدفع عبر الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لا تتردد في التواصل معنا إذا كانت لديك أي أسئلة.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject' => 'تم إنشاء الفاتورة المتكررة {invoice_number}',
        'body'    => 'مرحباً،<br /><br />تم إنشاء الفاتورة <strong>{invoice_number}</strong> تلقائياً بناءً على دورة التكرار الخاصة بـ {customer_name}.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة عبر الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject' => 'تمت مشاهدة الفاتورة {invoice_number}',
        'body'    => 'مرحباً،<br /><br />شاهد {customer_name} الفاتورة <strong>{invoice_number}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة عبر الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject' => 'إيصال سداد الفاتورة {invoice_number}',
        'body'    => 'مرحباً {customer_name}،<br /><br />شكراً لك على السداد. فيما يلي تفاصيل الدفعة:<br /><br />-------------------------------------------------<br />المبلغ: <strong>{transaction_total}</strong><br />التاريخ: <strong>{transaction_paid_date}</strong><br />رقم الفاتورة: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />يمكنك دائماً الاطلاع على تفاصيل الفاتورة عبر الرابط التالي: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />لا تتردد في التواصل معنا إذا كانت لديك أي أسئلة.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject' => 'تم استلام دفعة للفاتورة {invoice_number}',
        'body'    => 'مرحباً،<br /><br />سجّل {customer_name} دفعة للفاتورة <strong>{invoice_number}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل الفاتورة عبر الرابط التالي: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject' => 'تذكير بفاتورة الشراء {bill_number}',
        'body'    => 'مرحباً،<br /><br />هذا تذكير بفاتورة الشراء <strong>{bill_number}</strong> المستحقة للمورد {vendor_name}.<br /><br />إجمالي فاتورة الشراء هو {bill_total}، وتاريخ استحقاقها <strong>{bill_due_date}</strong>.<br /><br />يمكنك الاطلاع على تفاصيل فاتورة الشراء عبر الرابط التالي: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject' => 'تم إنشاء فاتورة الشراء المتكررة {bill_number}',
        'body'    => 'مرحباً،<br /><br />تم إنشاء فاتورة الشراء <strong>{bill_number}</strong> تلقائياً بناءً على دورة التكرار الخاصة بالمورد {vendor_name}.<br /><br />يمكنك الاطلاع على تفاصيل فاتورة الشراء عبر الرابط التالي: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject' => 'إيصالك من {company_name}',
        'body'    => 'مرحباً {contact_name}،<br /><br />شكراً لك على الدفع. <br /><br />يمكنك الاطلاع على تفاصيل الدفعة عبر الرابط التالي: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />لا تتردد في التواصل معنا إذا كانت لديك أي أسئلة.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject' => 'دفعة من {company_name}',
        'body'    => 'مرحباً {contact_name}،<br /><br />لقد أجرينا الدفعة التالية. <br /><br />يمكنك الاطلاع على تفاصيل الدفعة عبر الرابط التالي: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />لا تتردد في التواصل معنا إذا كانت لديك أي أسئلة.<br /><br />مع أطيب التحيات،<br />{company_name}',
    ],

];
