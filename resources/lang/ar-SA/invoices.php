<?php

return [

    'invoice_number'        => 'رقم فاتورة البيع',
    'invoice_date'          => 'تاريخ فاتورة البيع',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_to'               => 'فاتورة الشراء إلى',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المجموع الجزئي',
    'discount'              => 'الخصم',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'الإجمالي',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',

    'show_discount'         => 'خصم :discount%',
    'add_discount'          => 'إضافة خصم',
    'discount_desc'         => 'من المجموع الجزئي',

    'payment_due'           => 'استحقاق الدفع',
    'paid'                  => 'مدفوع',
    'histories'             => 'سجلات',
    'payments'              => 'المدفوعات',
    'add_payment'           => 'إضافة مدفوعات',
    'mark_paid'             => 'التحديد كمدفوع',
    'mark_sent'             => 'التحديد كمرسل',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'مسودة',
        'sent'              => 'تم الإرسال',
        'viewed'            => 'المشاهدات',
        'approved'          => 'تمت الموافقة',
        'partial'           => 'جزئي',
        'paid'              => 'مدفوع',
    ],

    'messages' => [
        'email_sent'        => 'تم إرسال الفاتورة بنجاح!',
        'marked_sent'       => 'تم تحديد الفاتورة كفاتورة مرسلة بنجاح!',
        'email_required'    => 'لا يوجد عنوان البريد إلكتروني لهذا العميل!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Created on :date',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'قمت باستلام هذه الرسالة لأنه لديك فاتورة قادمة بقيمة :amount للعميل :customer.',
        'button'            => 'الدفع الآن',
    ],

];
