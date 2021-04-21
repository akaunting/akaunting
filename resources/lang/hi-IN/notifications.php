<?php

return [

    'whoops'              => 'ओह!',
    'hello'               => 'नमस्कार!',
    'salutation'          => 'सादर,<br> :company_name',
    'subcopy'             => 'यदि आपको ":text" बटन पर क्लिक करने में समस्या हो रही है, तो नीचे दिए गए URL को अपने वेब ब्राउज़र में कॉपी और पेस्ट करें: [: url] (: url)',

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

];
