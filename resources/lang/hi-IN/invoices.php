<?php

return [

    'invoice_number'        => 'चालान संख्या',
    'invoice_date'          => 'चालान तारीख',
    'invoice_amount'        => 'चालान राशि',
    'total_price'           => 'कुल कीमत',
    'due_date'              => 'नियत तारीख',
    'order_number'          => 'क्रम संख्या',
    'bill_to'               => 'बिल प्राप्तकर्ता',

    'quantity'              => 'मात्रा',
    'price'                 => 'कीमत',
    'sub_total'             => 'पूर्ण योग',
    'discount'              => 'छूट',
    'item_discount'         => 'पंक्ति डिस्काउंट',
    'tax_total'             => 'कुल कर',
    'total'                 => 'कुल',

    'item_name'             => 'वस्तु का नाम|वस्तु का नाम',

    'show_discount'         => ':discount% छूट',
    'add_discount'          => 'छूट जोड़ें',
    'discount_desc'         => 'पूर्ण योग का',

    'payment_due'           => 'भुगतान राशि',
    'paid'                  => 'भुगतान किया है',
    'histories'             => 'इतिहास',
    'payments'              => 'भुगतान',
    'add_payment'           => 'भुगतान जोड़ें',
    'mark_paid'             => 'मार्क करे की भुगतान किया हुआ है',
    'mark_sent'             => 'मार्क करे की भेजा गया',
    'mark_viewed'           => 'मार्क किया हुआ देखे',
    'mark_cancelled'        => 'रद्द किए हुए में मार्क करे',
    'download_pdf'          => 'डाउनलोड PDF',
    'send_mail'             => 'ईमेल भेजें',
    'all_invoices'          => 'सभी चालान देखने के लिए लॉगिन करें',
    'create_invoice'        => 'चालान बनाएँ',
    'send_invoice'          => 'चालान भेजें',
    'get_paid'              => 'भुगतान प्राप्त करना',
    'accept_payments'       => 'ऑनलाइन भुगतान स्वीकार करें',

    'messages' => [
        'email_required'    => 'इस ग्राहक के लिए कोई ईमेल पता नहीं!',
        'draft'             => 'यह एक <b>ड्राफ्ट</b> चालान है और इसे भेजे जाने के बाद चार्ट में प्रतिबिंबित होगा।',

        'status' => [
            'created'       => ':date को बनाया गया',
            'viewed'        => 'देखा गया',
            'send' => [
                'draft'     => 'नहीं भेजा गया',
                'sent'      => ':date को भेजें',
            ],
            'paid' => [
                'await'     => 'भुगतान का इंतजार',
            ],
        ],
    ],

];
