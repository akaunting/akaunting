<?php

return [

    'company' => [
        'description'                => 'कंपनी का नाम, ईमेल, पता, कर संख्या आदि बदलें',
        'name'                       => 'नाम',
        'email'                      => 'ईमेल',
        'phone'                      => 'फ़ोन',
        'address'                    => 'पता',
        'edit_your_business_address' => 'अपना व्यावसायिक पता संपादित करें',
        'logo'                       => 'लोगो',
    ],

    'localisation' => [
        'description'       => 'वित्तीय वर्ष, समय क्षेत्र, तिथि प्रारूप और अधिक स्थानीय सेट करें',
        'financial_start'   => 'वित्तीय वर्ष प्रारंभ',
        'timezone'          => 'समय क्षेत्र',
        'financial_denote' => [
            'title'         => 'वित्तीय वर्ष की देन',
            'begins'        => 'जिस वर्ष से यह शुरू होता है',
            'ends'          => 'जिस वर्ष तक यह समाप्त हो जाता है',
        ],
        'date' => [
            'format'        => 'तारीख प्रारूप',
            'separator'     => 'तारीख विभाजक',
            'dash'          => 'डेश (-)',
            'dot'           => 'डॉट (.)',
            'comma'         => 'कॉमा (,)',
            'slash'         => 'फारवर्ड स्‍लैश (/)',
            'space'         => 'खाली जगह ( )',
        ],
        'percent' => [
            'title'         => 'प्रतिशत (%) स्थान',
            'before'        => 'नंबर से पहले',
            'after'         => 'नंबर के बाद',
        ],
        'discount_location' => [
            'name'          => 'छूट का स्थान',
            'item'          => 'रेखा पर',
            'total'         => 'कुल पर',
            'both'          => 'लाइन और कुल दोनों पर',
        ],
    ],

    'invoice' => [
        'description'       => 'चालान उपसर्ग, संख्या, पद, आधार लेख आदि को अनुकूलित करें',
        'prefix'            => 'संख्या उपसर्ग',
        'digit'             => 'संख्या अंक',
        'next'              => 'अगला नंबर',
        'logo'              => 'लोगो',
        'custom'            => 'कस्टम',
        'item_name'         => 'वस्तु का नाम',
        'item'              => 'वस्तु',
        'product'           => 'उत्पाद',
        'service'           => 'सेवाएं',
        'price_name'        => 'मूल्य का नाम',
        'price'             => 'कीमत',
        'rate'              => 'मूल्य',
        'quantity_name'     => 'मात्रा नाम',
        'quantity'          => 'मात्रा',
        'payment_terms'     => 'भुगतान की शर्तें',
        'title'             => 'शीर्षक',
        'subheading'        => 'उपशीर्षक',
        'due_receipt'       => 'प्राप्ति पर देय',
        'due_days'          => ':days दिनों के भीतर देय',
        'choose_template'   => 'चालान टेम्पलेट चुनें',
        'default'           => 'पूर्व निर्धारित',
        'classic'           => 'क्लासिक',
        'modern'            => 'नवीन',
        'hide'              => [
            'item_name'         => 'वस्तु का नाम छिपाएं',
            'item_description'  => 'वस्तु का विवरण छिपाएँ',
            'quantity'          => 'मात्रा छिपाएं',
            'price'             => 'मूल्य छिपाएं',
            'amount'            => 'राशि छिपाएं',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'स्थानांतरण टेम्पलेट चुनें',
        'second'            => 'दूसरा',
        'third'             => 'तीसरा',
    ],

    'default' => [
        'description'       => 'मूल खाता, मुद्रा, आपकी कंपनी की भाषा',
        'list_limit'        => 'प्रति पृष्ठ रेकार्ड',
        'use_gravatar'      => 'Gravatar का उपयोग करें',
        'income_category'   => 'आय श्रेणी',
        'expense_category'  => 'व्यय की श्रेणी',
    ],

    'email' => [
        'description'       => 'प्रेषण प्रोटोकॉल और ईमेल टेम्प्लेट बदलें',
        'protocol'          => 'प्रोटोकॉल',
        'php'               => 'PHP मेल',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP होस्ट',
            'port'          => 'SMTP पोर्ट',
            'username'      => 'SMTP यूजरनाम',
            'password'      => 'SMTP पासवर्ड',
            'encryption'    => 'SMTP सिक्योरिटी',
            'none'          => 'कोई नहीं',
        ],
        'sendmail'          => 'सेंडमेल',
        'sendmail_path'     => 'सेंडमेल पाथ',
        'log'               => 'ईमेल लॉग करें',

        'templates' => [
            'subject'                   => 'विषय',
            'body'                      => 'बॉडी',
            'tags'                      => '<strong> उपलब्ध टैग:</strong> :tag_list',
            'invoice_new_customer'      => 'नया चालान टेम्प्लेट (ग्राहक को भेजा गया)',
            'invoice_remind_customer'   => 'चालान स्मरणपत्र टेम्पलेट (ग्राहक को भेजा गया)',
            'invoice_remind_admin'      => 'चालान स्मरणपत्र टेम्पलेट (व्यवस्थापक को भेजा गया)',
            'invoice_recur_customer'    => 'चालान आवर्ती टेम्पलेट (ग्राहक को भेजा गया)',
            'invoice_recur_admin'       => 'चालान आवर्ती टेम्पलेट (व्यवस्थापक को भेजा गया)',
            'invoice_payment_customer'  => 'भुगतान प्राप्त टेम्प्लेट (ग्राहक को भेजा गया)',
            'invoice_payment_admin'     => 'भुगतान प्राप्त टेम्प्लेट (व्यवस्थापक को भेजा गया)',
            'bill_remind_admin'         => 'बिल स्मरणपत्र टेम्पलेट (व्यवस्थापक को भेजा गया)',
            'bill_recur_admin'          => 'बिल आवर्ती टेम्पलेट (व्यवस्थापक को भेजा गया)',
            'revenue_new_customer'      => 'आय प्राप्त टेम्प्लेट (ग्राहक को भेजा गया)',
        ],
    ],

    'scheduling' => [
        'name'              => 'सिडुलिंग',
        'description'       => 'आवर्ती के लिए स्वचालित कमांड और आदेश',
        'send_invoice'      => 'चालान स्मरणपत्र भेजें',
        'invoice_days'      => 'नियत दिनों के बाद भेजें',
        'send_bill'         => 'बिल स्मरणपत्र भेजें',
        'bill_days'         => 'नियत दिनों से पहले भेजें',
        'cron_command'      => 'क्रोंन कमांड',
        'schedule_time'     => 'चलने का समय',
    ],

    'categories' => [
        'description'       => 'आय, व्यय और मद के लिए असीमित श्रेणियां',
    ],

    'currencies' => [
        'description'       => 'मुद्राएं बनाएं और प्रबंधित करें और उनकी दरें निर्धारित करें',
    ],

    'taxes' => [
        'description'       => 'निश्चित, साधारण, सम्मिलित और यौगिक कर की दरें',
    ],

];
