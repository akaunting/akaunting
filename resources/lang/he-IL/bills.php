<?php

return [

    'bill_number'       => 'מספר החשבון',
    'bill_date'         => 'תאריך החשבון',
    'total_price'       => 'סה"כ מחיר',
    'due_date'          => 'תאריך יעד',
    'order_number'      => 'מספר הזמנה',
    'bill_from'         => 'חשבון מ',

    'quantity'          => 'כמות',
    'price'             => 'מחיר',
    'sub_total'         => 'סכום ביניים',
    'discount'          => 'הנחה',
    'tax_total'         => 'סה כ מס',
    'total'             => 'סה"כ',

    'item_name'         => 'פריט שם | שמות הפריטים',

    'show_discount'     => ':discount % הנחה',
    'add_discount'      => 'הוסף הנחה',
    'discount_desc'     => 'של ביניים',

    'payment_due'       => 'תשלום בשל',
    'amount_due'        => 'סכום לחיוב',
    'paid'              => 'שולם',
    'histories'         => 'היסטוריה',
    'payments'          => 'תשלומים',
    'add_payment'       => 'הוספת תשלום',
    'mark_received'     => 'סימון תקבול',
    'download_pdf'      => 'הורדת PDF',
    'send_mail'         => 'שלח דואר אלקטרוני',

    'status' => [
        'draft'         => 'טיוטה',
        'received'      => 'התקבל',
        'partial'       => 'חלקי',
        'paid'          => 'שולם',
    ],

    'messages' => [
        'received'      => 'חשבונות שסומנו התקבלו בהצלחה!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
