<?php

return [

    'invoice_number'    => 'מספר חשבונית',
    'invoice_date'      => 'תאריך חשבונית',
    'total_price'       => 'סה"כ מחיר',
    'due_date'          => 'תאריך',
    'order_number'      => 'מספר הזמנה',
    'bill_to'           => 'חשבון עבור',

    'quantity'          => 'כמות',
    'price'             => 'מחיר',
    'sub_total'         => 'סכום ביניים',
    'discount'          => 'הנחה',
    'tax_total'         => 'סה"כ מס',
    'total'             => 'סה"כ',

    'item_name'         => 'פריט שם | שמות הפריטים',

    'show_discount'     => ':discount % הנחה',
    'add_discount'      => 'הוסף הנחה',
    'discount_desc'     => 'של ביניים',

    'payment_due'       => 'תשלום בשל',
    'paid'              => 'שולם',
    'histories'         => 'היסטוריה',
    'payments'          => 'תשלומים',
    'add_payment'       => 'הוספת תשלום',
    'mark_paid'         => 'סימון תקבול',
    'mark_sent'         => 'סמן כנשלח',
    'download_pdf'      => 'הורדת PDF',
    'send_mail'         => 'שלח דואר אלקטרוני',
    'all_invoices'      => 'Login to view all invoices',

    'statuses' => [
        'draft'         => 'טיוטה',
        'sent'          => 'נשלח',
        'viewed'        => 'נצפה',
        'approved'      => 'אושר',
        'partial'       => 'חלקי',
        'paid'          => 'שולם',
    ],

    'messages' => [
        'email_sent'     => 'החשבונית נשלחה בהצלחה באמצעות הדואר האלקטרוני!',
        'marked_sent'    => 'חשבונית סומנה שנשלחה בהצלחה!',
        'email_required' => 'אין דואר אלקטרוני מעודכן ללקוח זה!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'אתה מקבל את הודעת האימייל הזו מפני שקיימות :amount חשבוניות חדשות עבור הלקוח :customer.',
        'button'        => 'שלם עכשיו',
    ],

];
