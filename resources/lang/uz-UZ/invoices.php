<?php

return [

    'invoice_number'        => 'Hisob raqami',
    'invoice_date'          => 'Hisob-fakturaning sanasi',
    'total_price'           => 'Umumiy narx',
    'due_date'              => 'Bajarish muddati',
    'order_number'          => 'Buyurtma raqami',
    'bill_to'               => 'Hisob kimning nomiga',

    'quantity'              => 'Miqdori',
    'price'                 => 'Narxi',
    'sub_total'             => 'Umumiy son',
    'discount'              => 'Chegirma',
    'tax_total'             => 'Jami soliq',
    'total'                 => 'Soliq',

    'item_name'             => 'Tovar nomi | Tovar nomlari',

    'show_discount'         => ': chegirma% chegirma',
    'add_discount'          => 'Chegirma qo\'shing',
    'discount_desc'         => 'yakunini chiqarish',

    'payment_due'           => 'To\'lov muddati',
    'paid'                  => 'To\'langan',
    'histories'             => 'Tarixlar',
    'payments'              => 'To\'lovlar',
    'add_payment'           => 'To\'lovni qo\'shish',
    'mark_paid'             => 'To\'langan pul belgisi',
    'mark_sent'             => 'Yuborilgan pul belgisi',
    'download_pdf'          => 'PDF-ni yuklab oling',
    'send_mail'             => 'Elektron pochta xabarini yuboring',
    'all_invoices'          => 'Barcha hisoblarni ko\'rish uchun tizimga kiring',
    'create_invoice'        => 'Hisoblarni yaratish',
    'send_invoice'          => 'Hisob-fakturani yuborish',
    'get_paid'              => 'To\'lang',
    'accept_payments'       => 'Onlayn to\'lovlarni qabul qiling',

    'statuses' => [
        'draft'             => 'Qoralama',
        'sent'              => 'Yuborildi',
        'viewed'            => 'Ko\'rilgan',
        'approved'          => 'Tasdiqlangan',
        'partial'           => 'Qisman',
        'paid'              => 'To\'langan',
    ],

    'messages' => [
        'email_sent'        => 'Hisob elektron pochtasiga yuborildi!',
        'marked_sent'       => 'Hisob yuborilgan deb belgilangan!',
        'marked_paid'       => 'Hisob to\'langan deb belgilangan!',
        'email_required'    => 'Ushbu mijoz uchun elektron pochta manzili yo\'q!',
        'draft'             => 'Bu <b> QORALAMA VARIANTI </b> hisob-fakturasi bo\'lib, u yuborilganidan keyin grafikalarda aks etadi.',

        'status' => [
            'created'       => 'Yaratilgan sanasi: sana',
            'viewed'        => 'Ko\'rilgan',
            'send' => [
                'draft'     => 'Yuborilmadi',
                'sent'      => 'Yuborildi : sana',
            ],
            'paid' => [
                'await'     => 'To\'lovni kutish',
            ],
        ],
    ],

];
