<?php

return [

    'whoops'              => 'ओह!',
    'hello'               => 'नमस्कार!',
    'salutation'          => 'सादर,<br> :company_name',
    'subcopy'             => 'यदि आपको ":text" बटन पर क्लिक करने में समस्या हो रही है, तो नीचे दिए गए URL को अपने वेब ब्राउज़र में कॉपी और पेस्ट करें: [: url] (: url)',
    'mark_read'           => 'पढ़ा हुआ चिह्नित करें',
    'mark_read_all'       => 'सब पढ़ा हुआ चिह्नित करें',
    'empty'               => 'वाह, अधिसूचना(नोटिफिकेशन) शून्य!',
    'new_apps'            => 'नया ऐप|नए ऐप्स',

    'update' => [

        'mail' => [

            'title'         => '⚠️ :domain पर अपडेट विफल',
            'description'   => ':current_version से :new_version तक :alias का अद्यतन निम्न संदेश के साथ <strong>:step</strong> चरण में विफल रहा: :error_message',

        ],

        'slack' => [

            'description'   => 'अद्यतन(अपडेट) विफल हुआ :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'डाउनलोड तैयार है',
            'description'   => 'फ़ाइल निम्न लिंक से डाउनलोड करने के लिए तैयार है:',

        ],

        'failed' => [

            'title'         => 'डाउनलोड विफल',
            'description'   => 'निम्नलिखित समस्या के कारण फ़ाइल बनाने में असमर्थ:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'आयात पूरा हुआ',
            'description'   => 'आयात पूरा हो गया है और रिकॉर्ड आपके पैनल में उपलब्ध हैं।',

        ],

        'failed' => [

            'title'         => 'आयात विफल रहा',
            'description'   => 'निम्नलिखित समस्याओं के कारण फ़ाइल आयात करने में सक्षम नहीं:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'निर्यात तैयार है',
            'description'   => 'निर्यात फ़ाइल निम्न लिंक से डाउनलोड करने के लिए तैयार है:',

        ],

        'failed' => [

            'title'         => 'निर्यात विफल रहा',
            'description'   => 'निम्नलिखित समस्या के कारण निर्यात फ़ाइल बनाने में सक्षम नहीं:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'अमान्य :type ईमेल',
            'description'   => ':email ईमेल पता अमान्य बताया गया है, और व्यक्ति अक्षम कर दिया गया है। कृपया निम्नलिखित त्रुटि संदेश की जाँच करें और ईमेल पता ठीक करें:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'डाउनलोड तैयार है',
            'description'   => 'आपकी <strong>:type</strong> फ़ाइल <a href=":url" target="_blank"><strong>डाउनलोड</strong></a> के लिए तैयार है.',

        ],

        'download_failed' => [

            'title'         => 'डाउनलोड विफल',
            'description'   => 'कई समस्याओं के कारण फ़ाइल नहीं बनाई जा सकी। विवरण के लिए अपना ईमेल देखें।',

        ],

        'export_completed' => [

            'title'         => 'निर्यात तैयार है',
            'description'   => 'आपकी <strong>:type</strong> निर्यात फ़ाइल  <a href=":url" target="_blank"><strong>डाउनलोड</strong></a> करने के लिए तैयार है।',

        ],

        'export_failed' => [

            'title'         => 'निर्यात विफल रहा',
            'description'   => 'निम्नलिखित समस्या के कारण निर्यात फ़ाइल बनाने में सक्षम नहीं: :issues',

        ],

        'import_completed' => [

            'title'         => 'आयात पूरा हुआ',
            'description'   => 'आपका <strong>:type</strong> लाइन वाला <strong>:count</strong>  डेटा सफलतापूर्वक आयात किया गया है।',

        ],

        'import_failed' => [

            'title'         => 'आयात विफल रहा',
            'description'   => 'कई समस्याओं के कारण फ़ाइल आयात करने में सक्षम नहीं है। विवरण के लिए अपना ईमेल देखें।',

        ],

        'new_apps' => [

            'title'         => 'नया ऐप',
            'description'   => '<strong>:name</strong> ऐप बंद हो गया है। विवरण देखने के लिए आप <a href=":url">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_new_customer' => [

            'title'         => 'नया चालान',
            'description'   => '<strong>:invoice_number</strong> चालान बनाया जाता है। विवरण देखने और भुगतान के लिए आगे बढ़ने के लिए आप <a href=":invoice_portal_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_remind_customer' => [

            'title'         => 'चालान अतिदेय',
            'description'   => '<strong>:invoice_number</strong> चालान <strong>:invoice_due_date</strong> देय था। विवरण देखने और भुगतान के लिए आगे बढ़ने के लिए आप <a href=":invoice_portal_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_remind_admin' => [

            'title'         => 'चालान अतिदेय',
            'description'   => '<strong>:invoice_number</strong> चालान <strong>:invoice_due_date</strong> देय था। विवरण देखने और भुगतान के लिए आगे बढ़ने के लिए आप <a href=":invoice_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_recur_customer' => [

            'title'         => 'नया आवर्ती चालान',
            'description'   => '<strong>:invoice_number</strong> चालान आपके आवर्ती मंडली के आधार पर बनाया जाता है। विवरण देखने और भुगतान के लिए आगे बढ़ने के लिए आप <a href=":invoice_portal_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_recur_admin' => [

            'title'         => 'नया आवर्ती चालान',
            'description'   => '<strong>:invoice_number</strong> चालान <strong>:customer_name</strong> आवर्ती मंडली के आधार पर बनाया जाता है। विवरण देखने के लिए आप <a href=":invoice_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_view_admin' => [

            'title'         => 'चालान देखा गया',
            'description'   => '<strong>:customer_name</strong> ने <strong>:invoice_number</strong> चालान देख लिया है। विवरण देखने के लिए आप <a href=":invoice_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'revenue_new_customer' => [

            'title'         => 'भुगतान प्राप्त',
            'description'   => '<strong>:invoice_number</strong> चालान के भुगतान के लिए धन्यवाद। विवरण देखने के लिए आप <a href=":invoice_portal_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_payment_customer' => [

            'title'         => 'भुगतान प्राप्त',
            'description'   => '<strong>:invoice_number</strong> चालान के भुगतान के लिए धन्यवाद। विवरण देखने के लिए आप <a href=":invoice_portal_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invoice_payment_admin' => [

            'title'         => 'भुगतान प्राप्त',
            'description'   => ':customer_name ने <strong>:invoice_number</strong> चालान के लिए भुगतान रिकॉर्ड किया। विवरण देखने के लिए आप <a href=":invoice_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'bill_remind_admin' => [

            'title'         => 'बिल अतिदेय',
            'description'   => '<strong>:bill_number</strong> बिल देय था <strong>:bill_due_date</strong>। विवरण देखने के लिए आप <a href=":bill_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'bill_recur_admin' => [

            'title'         => 'नया आवर्ती बिल',
            'description'   => '<strong>:bill_number</strong> बिल <strong>:vendor_name</strong> आवर्ती मंडली के आधार पर बनाया गया है। विवरण देखने के लिए आप <a href=":bill_admin_link">यहां क्लिक</a> कर सकते हैं।',

        ],

        'invalid_email' => [

            'title'         => 'अमान्य :type ईमेल',
            'description'   => '<strong>:email</strong> ईमेल पता अमान्य बताया गया है, और व्यक्ति अक्षम कर दिया गया है। कृपया ईमेल पता जांचें और ठीक करें।',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type इस अधिसूचना को पढ़ा है!',
        'mark_read_all'         => ':type सभी सूचनाएं पढ़ता है!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'फ़ायरफ़ॉक्स आइकन कॉन्फ़िगरेशन',
            'description'  => '<span class="font-medium">यदि आपके चिह्न प्रकट नहीं होते हैं तो कृपया;</span> <br /> <span class="font-medium">कृपया उपरोक्त आपके चयनों के बजाय पृष्ठों को अपने स्वयं के फ़ॉन्ट चुनने की अनुमति दें</span> <br /><br /> <span class="font-bold"> सेटिंग्स (प्राथमिकताएं)> फ़ॉन्ट्स> उन्नत</span>',

        ],

    ],

];
