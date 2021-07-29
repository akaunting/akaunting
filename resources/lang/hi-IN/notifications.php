<?php

return [

    'whoops'              => 'ओह!',
    'hello'               => 'नमस्कार!',
    'salutation'          => 'सादर,<br> :company_name',
    'subcopy'             => 'यदि आपको ":text" बटन पर क्लिक करने में समस्या हो रही है, तो नीचे दिए गए URL को अपने वेब ब्राउज़र में कॉपी और पेस्ट करें: [: url] (: url)',
    'reads'               => 'पढ़ें|पढ़ें',
    'read_all'            => 'सब पढ़ो',
    'mark_read'           => 'पढ़ा हुआ चिह्नित करें',
    'mark_read_all'       => 'सब पढ़ा हुआ चिह्नित करें',
    'new_apps'            => 'नया ऐप|नए ऐप्स',
    'upcoming_bills'      => 'आगामी बिल',
    'recurring_invoices'  => 'आवर्ती चालान',
    'recurring_bills'     => 'आवर्ती बिल',

    'update' => [

        'mail' => [

            'subject' => ':domain पर अपडेट विफल',
            'message' => 'निम्न संदेश के साथ :alias का :current_version से :new_version तक का अद्यतन <strong> :step </ strong> चरण में विफल रहा: :error_message',

        ],

        'slack' => [

            'message' => ':domain पर अपडेट विफल',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'आयात पूरा हुआ',
            'description'       => 'आयात पूरा हो गया है और रिकॉर्ड आपके पैनल में उपलब्ध हैं।',

        ],

        'failed' => [

            'subject'           => 'आयात विफल रहा',
            'description'       => 'निम्नलिखित समस्याओं के कारण फ़ाइल आयात करने में सक्षम नहीं:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'निर्यात तैयार है',
            'description'       => 'निर्यात फ़ाइल निम्न लिंक से डाउनलोड करने के लिए तैयार है:',

        ],

        'failed' => [

            'subject'           => 'निर्यात विफल रहा',
            'description'       => 'निम्नलिखित समस्या के कारण निर्यात फ़ाइल बनाने में सक्षम नहीं:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type इस अधिसूचना को पढ़ा है!',
        'mark_read_all'         => ':type सभी सूचनाएं पढ़ता है!',
        'new_app'               => ':type ऐप प्रकाशित।',
        'export'                => 'आपकी <b>:type</b> निर्यात फ़ाइल  <a href=":url" target="_blank"><b>डाउनलोड</b></a> करने के लिए तैयार है।',
        'import'                => 'आपका <b>:type</b> लाइन वाला <b>:count</b>  डेटा सफलतापूर्वक आयात किया गया है।',

    ],
];
