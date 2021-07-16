<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute को स्वीकार किया जाना चाहिए।',
    'active_url' => ':attribute एक मान्य URL नहीं है।',
    'after' => ':attribute, :date के बाद की एक तारीख होनी चाहिए।',
    'after_or_equal' => ':attribute के बराबर या उसके बाद की :date होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर हो सकते हैं।',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्या, और डैश हो सकते हैं।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्याएं हो सकती हैं।',
    'array' => ':attribute एक सरणी होनी चाहिए।',
    'before' => ':attribute, :date से पहले की एक तारीख होनी चाहिए।',
    'before_or_equal' => ':attribute के बराबर या उसके पहले की :date होनी चाहिए।',
    'between' => [
        'numeric' => ':attribute, :min और :max के बीच होना चाहिए।',
        'file' => ':attribute, :min और :max किलोबाइट के बीच होना चाहिए।',
        'string' => ':attribute, :min और :max वर्णों के बीच होना चाहिए।',
        'array' => ':attribute, :min और :max वस्तुओ के बीच होनी चाहिए।',
    ],
    'boolean' => ':attribute फील्ड सही या गलत होना चाहिए।',
    'confirmed' => ':attribute पुष्टिकरण मेल नहीं खा रहा है।',
    'current_password' => 'पासवर्ड ग़लत है',
    'date' => ':attribute एक मान्य तारीख नहीं है।',
    'date_equals' => ':attribute तारीख :date के बराबर होना चाहिए।',
    'date_format' => ':attribute फॉर्मेट :format से मेल नहीं खा रहा है।',
    'different' => ':attribute और :other <strong>अलग</strong> होना चाहिए।',
    'digits' => ':attribute, :digits अंक होना चाहिए।',
    'digits_between' => ':attribute, :min और :max अंकों के बीच होना चाहिए।',
    'dimensions' => ':attribute में अमान्य छवि पैमाना हैं।',
    'distinct' => ':attribute फील्ड का एक डुप्लिकेट मान होता है।',
    'email' => ':attribute एक मान्य <strong>ईमेल पता</strong> होना चाहिए।',
    'ends_with' => ':attribute को निम्न में से किसी एक के साथ समाप्त होना चाहिए: :values',
    'exists' => 'चुना गया :attribute अमान्य है।',
    'file' => ':attribute <strong>फ़ाइल</strong> होना चाहिए।',
    'filled' => ':attribute फ़ील्ड में <strong>मान</strong> होना चाहिए।',
    'gt' => [
        'numeric' => ':attribute :value से अधिक होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से अधिक होनी चाहिए।',
        'string' => ':attribute :value  वर्णों से बड़ा होना चाहिए।',
        'array' => ':attribute में :value से अधिक आइटम होने चाहिए।',
    ],
    'gte' => [
        'numeric' => ':attribute :value से बड़ा या उसके बराबर होना चाहिए।',
        'file' => ':attribute :value किलोबाइट से बड़ा या उसके बराबर होना चाहिए।',
        'string' => ':attribute :value वर्णों से बड़ा या उसके बराबर होना चाहिए।',
        'array' => ':attribute में :value या अधिक आइटम होने चाहिए।',
    ],
    'image' => ':attribute एक <strong>छवि</strong> होना चाहिए।',
    'in' => 'चुना गया :attribute अमान्य है।',
    'in_array' => ':attribute फील्ड, :other में मौजूद नहीं है।',
    'integer' => ':attribute एक <strong>पूर्णांक</strong> होनी चाहिए।',
    'ip' => ':attribute एक मान्य IP पता होना चाहिए।',
    'ipv4' => ':attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6' => ':attribute एक मान्य IPv6 पता होना चाहिए।',
    'json' => ':attribute एक मान्य JSON स्ट्रिंग होना चाहिए।',
    'lt' => [
        'numeric' => ':attribute :value से कम होना चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम होना चाहिए।',
        'string' => ':attribute :value  वर्णों से कम होनी चाहिए।',
        'array' => ':attribute में :value से कम आइटम होने चाहिए।',
    ],
    'lte' => [
        'numeric' => ':attribute :value से कम या उसके बराबर होना चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम या उसके बराबर होना चाहिए।',
        'string' => ':attribute :value वर्णों से कम या बराबर होनी चाहिए।',
        'array' => ':attribute में :value आइटम से अधिक नहीं होना चाहिए।',
    ],
    'max' => [
        'numeric' => ':attribute, :max से बड़ा नहीं हो सकता है।',
        'file' => ':attribute :max किलोबाइट से बड़ा नहीं हो सकता है।',
        'string' => ':attribute, :max वर्णों से बड़ा नहीं हो सकता है।',
        'array' => ':attribute, :max आइटमों से अधिक नहीं हो सकता है।',
    ],
    'mimes' => ':attribute एक प्रकार की फ़ाइल: :values होना चाहिए।',
    'mimetypes' => ':attribute एक प्रकार की फ़ाइल :values होना चाहिए।',
    'min' => [
        'numeric' => ':attribute कम से कम :min होना चाहिए।',
        'file' => ':attribute कम से कम :min किलोबाइट होना चाहिए।',
        'string' => ':attribute कम से कम :min वर्ण होना चाहिए।',
        'array' => ':attribute कम से कम :min आइटम होना चाहिए।',
    ],
    'multiple_of' => ':attribute :value की गुणज होनी चाहिए।',
    'not_in' => 'चुना गया :attribute अमान्य है।',
    'not_regex' => ':attribute प्रारूप अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए।',
    'password' => 'पासवर्ड ग़लत है',
    'present' => ':attribute फ़ील्ड <strong>उपस्थित</strong> होना चाहिए।',
    'regex' => ':attribute प्रारूप <strong>अमान्य</strong> है।',
    'required' => ':attribute फ़ील्ड <strong>आवश्यक</strong> है।',
    'required_if' => ':attribute फ़ील्ड आवश्यक होता है जब :other :value होता है।',
    'required_unless' => ':attribute फील्ड आवश्यक होता है जब :other, :values में नहीं होता है।',
    'required_with' => ':attribute फ़ील्ड आवश्यक होता है जब :values मौजूद होता है।',
    'required_with_all' => ':attribute फ़ील्ड आवश्यक होता है जब :values मौजूद होता है।',
    'required_without' => ':attribute फील्ड आवश्यक होता है जब :values मौजूद नहीं होता है।',
    'required_without_all' => ':attribute फील्ड आवश्यक होता है जब एक भी :values मौजूद नहीं होता है।',
    'prohibited' => ':attribute फ़ील्ड निषिद्ध है।',
    'prohibited_if' => ':attribute फ़ील्ड निषिद्ध है जब :other :value है।',
    'prohibited_unless' => ':attribute फ़ील्ड निषिद्ध है जब तक कि :other :values में न हो।',
    'same' => ':attribute और :other मेल खाना चाहिए।',
    'size' => [
        'numeric' => ':attribute, :size होना चाहिए।',
        'file' => ':attribute, :size किलोबाइट होना चाहिए।',
        'string' => ':attribute <strong>:size वर्ण</strong> होना चाहिए।',
        'array' => ':attribute में :size आइटम होने चाहिए।',
    ],
    'starts_with' => ':attribute निम्न में से किसी एक से शुरू होना चाहिए: :values.',
    'string' => ':attribute का <strong>स्ट्रिंग</strong> होना चाहिए।',
    'timezone' => ':attribute एक मान्य क्षेत्र होना चाहिए।',
    'unique' => ':attribute पहले से ही <strong>लिया गया</strong> है।',
    'uploaded' => 'अपलोड करने के लिए :attribute <strong> विफल </strong> हो गया  है।',
    'url' => ':attribute प्रारूप <strong>अमान्य</strong> है।',
    'uuid' => ':attribute एक मान्य UUID होना चाहिए।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'अनुकूल-संदेश',
        ],
        'invalid_currency'      => ':attribute कोड अमान्य है।',
        'invalid_amount'        => 'राशि :attribute अमान्य है।',
        'invalid_extension'     => 'फ़ाइल एक्सटेंशन अमान्य है।',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
