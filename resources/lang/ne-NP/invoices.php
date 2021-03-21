<?php

return [

    'invoice_number'        => 'चलानी नम्बर',
    'invoice_date'          => 'चलानी मिति',
    'total_price'           => 'जम्मा रकम',
    'due_date'              => 'बक्यौता मिति',
    'order_number'          => 'माग सङ्ख्या',
    'bill_to'               => 'लाई बिल',

    'quantity'              => 'परिमाण',
    'price'                 => 'मूल्य',
    'sub_total'             => 'उप कुल',
    'discount'              => 'छुट',
    'item_discount'         => 'सिधा छुट',
    'tax_total'             => 'जम्मा कर',
    'total'                 => 'जम्मा',

    'item_name'             => 'सामानको  नाम|सामानहरुको नाम',

    'show_discount'         => ':discount% छुट',
    'add_discount'          => 'छुट थप्नुहोस',
    'discount_desc'         => 'उप कुलको',

    'payment_due'           => 'भुक्तानी बक्यौता ',
    'paid'                  => 'तिरेको',
    'histories'             => 'इतिहासहरू',
    'payments'              => 'भुक्तानीहरू',
    'add_payment'           => 'भुक्तानी थप्नुहोस',
    'mark_paid'             => 'भुक्तानी अंकित',
    'mark_sent'             => 'प्रेषण अंकित',
    'mark_viewed'           => 'हेरिसकेको चिन्हित गर्नुहोस्',
    'mark_cancelled'        => 'रद्ध गरिएको चिन्ह लगाउनुहोस',
    'download_pdf'          => 'PDF डाउनलोड गर्नुहोस',
    'send_mail'             => 'इमेल गर्नुहोस',
    'all_invoices'          => 'सबै चलानीहरू हेर्न प्रवेश गर्नुहोस्',
    'create_invoice'        => 'चलानी बनाउनुहोस्',
    'send_invoice'          => 'चलानी पठाउनुहोस्',
    'get_paid'              => 'भुक्तानी पाउनुहोस्',
    'accept_payments'       => 'अनलाइन भुक्तानी स्वीकृत गर्नुहोस्',

    'messages' => [
        'email_required'    => 'यो ग्राहकको ईमेल ठेगाना छैन!',
        'draft'             => 'यो एउटा <b>मस्यौदा</b> चलानी हो र प्रेषित भएपछी मात्र यो चार्टमा देखिने छ |',

        'status' => [
            'created'       => ':date मा सिर्जित',
            'viewed'        => 'हेरिएको',
            'send' => [
                'draft'     => 'अप्रेषित',
                'sent'      => ':date मा प्रेषित',
            ],
            'paid' => [
                'await'     => 'प्रतिक्षित भुक्तानी',
            ],
        ],
    ],

];
