<?php

return [

    'bill_number'           => 'बिल संख्या',
    'bill_date'             => 'बिल तारीख',
    'bill_amount'           => 'बिल राशि',
    'total_price'           => 'कुल राशि',
    'due_date'              => 'नियत तारीख',
    'order_number'          => 'ऑर्डर संख्या',
    'bill_from'             => 'बिल से',

    'quantity'              => 'मात्रा',
    'price'                 => 'मूल्य',
    'sub_total'             => 'उप-योग',
    'discount'              => 'छूट',
    'item_discount'         => 'पंक्ति छूट',
    'tax_total'             => 'कुल कर',
    'total'                 => 'कुल',

    'item_name'             => 'वस्तु का नाम|वस्तुओं के नाम',
    'recurring_bills'       => 'आवर्ती बिल|आवर्ती बिल',

    'show_discount'         => ':discount% छूट',
    'add_discount'          => 'छूट जोड़ें',
    'discount_desc'         => 'उप-योग का',

    'payment_made'          => 'भुगतान किया गया',
    'payment_due'           => 'भुगतान देय',
    'amount_due'            => 'देय राशि',
    'paid'                  => 'भुगतान किया है',
    'histories'             => 'इतिहास',
    'payments'              => 'भुगतान',
    'add_payment'           => 'भुगतान जोड़ें',
    'mark_paid'             => 'भुगतान किया हुआ चिह्नित करें',
    'mark_received'         => 'प्राप्त किया हुआ चिह्नित करें',
    'mark_cancelled'        => 'रद्द किया हुआ चिह्नित करें',
    'download_pdf'          => 'डाउनलोड PDF',
    'send_mail'             => 'ईमेल भेजें',
    'create_bill'           => 'बिल बनाएं',
    'receive_bill'          => 'बिल प्राप्त करें',
    'make_payment'          => 'भुगतान करें',

    'form_description' => [
        'billing'           => 'बिलिंग विवरण आपके बिल में दिखाई देते हैं। बिल दिनांक का उपयोग डैशबोर्ड और रिपोर्ट में किया जाता है। उस दिनांक का चयन करें जिसे आप भुगतान करने की अपेक्षा करते हैं उसे देय दिनांक के रूप में चुनें।',
    ],

    'messages' => [
        'draft'             => 'यह एक <b>ड्राफ्ट</b> बिल है और इसे प्राप्त होने के बाद चार्ट पर प्रतिबिंबित किया जाएगा।',

        'status' => [
            'created'       => ':date को बनाया गया',
            'receive' => [
                'draft'     => 'प्राप्त नहीं किया गया',
                'received'  => ':date को प्राप्त किया गया',
            ],
            'paid' => [
                'await'     => 'भुगतान की प्रतीक्षा',
            ],
        ],
    ],

];
