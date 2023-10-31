<?php

return [

    'bill_number'           => 'מספר החשבון',
    'bill_date'             => 'תאריך החשבון',
    'bill_amount'           => 'סכום החשבון',
    'total_price'           => 'סה"כ מחיר',
    'due_date'              => 'תאריך יעד',
    'order_number'          => 'מספר הזמנה',
    'bill_from'             => 'חשבון מ',

    'quantity'              => 'כמות',
    'price'                 => 'מחיר',
    'sub_total'             => 'סכום ביניים',
    'discount'              => 'הנחה',
    'item_discount'         => 'הנחת שורה',
    'tax_total'             => 'סה"כ מס',
    'total'                 => 'סה"כ',

    'item_name'             => 'פריט שם | שמות הפריטים',
    'recurring_bills'       => 'חשבונות מחזוריים',

    'show_discount'         => ':discount % הנחה',
    'add_discount'          => 'הוסף הנחה',
    'discount_desc'         => 'של סכום ביניים',

    'payment_made'          => 'תשלום בוצע',
    'payment_due'           => 'מועד התשלום',
    'amount_due'            => 'סכום לחיוב',
    'paid'                  => 'שולם',
    'histories'             => 'היסטוריה',
    'payments'              => 'תשלומים',
    'add_payment'           => 'הוספת תשלום',
    'mark_paid'             => 'סמן כ-שולם',
    'mark_received'         => 'סמן שהתקבל',
    'mark_cancelled'        => 'סמן כ-בוטל',
    'download_pdf'          => 'הורדה כקובץ PDF',
    'send_mail'             => 'שלח דואר אלקטרוני',
    'create_bill'           => 'יצירת חשבונית',
    'receive_bill'          => 'קבלת חשבונית',
    'make_payment'          => 'ביצוע תשלום',

    'form_description' => [
        'billing'           => 'פרטים החיוב נמצאים בקבלה. תאריך החיוב מופיע בכל הדוחות. אנא בחר את התאריך כמועד התשלום',
    ],

    'messages' => [
        'draft'             => 'החשבון <B>בטיוטה</B> ויופיע בגרפים רק לאחר שיאושר',

        'status' => [
            'created'       => 'נוצר ב :date',
            'receive' => [
                'draft'     => 'לא התקבל',
                'received'  => 'התקבל ב :date',
            ],
            'paid' => [
                'await'     => 'ממתין לתשלום',
            ],
        ],
    ],

];
