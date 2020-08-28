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

    'accepted'             => ':attribute स्वीकार गरिनु पर्छ।',
    'active_url'           => ':attribute मान्य  URL होइन ।',
    'after'                => ':attribute   :date भन्दा पछि हुनुपर्छ।',
    'after_or_equal'       => ':attribute  :date भन्दा पछि वा बराबर हुनुपर्छ।',
    'alpha'                => ':attribute मा अक्षरहरु मात्र हुनसक्छ।',
    'alpha_dash'           => ':attribute मा अक्षर, संख्या र ड्यासहरू मात्र हुनसक्छ।',
    'alpha_num'            => ':attribute मा अक्षर र संख्याहरू मात्र हुनसक्छ।',
    'array'                => ':attribute array हुनुपर्छ।',
    'before'               => ':attribute  :date भन्दा अघि हुनुपर्छ।',
    'before_or_equal'      => ':attribute  :date भन्दा अघि वा बराबर हुनुपर्छ।',
    'between'              => [
        'numeric' => ':attribute :min र :max को बिचमा हुनुपर्छ।',
        'file'    => ':attribute :min र :max kilobytes को बिचमा हुनुपर्छ।',
        'string'  => ':attribute :min र :max वर्णको बिचमा हुनुपर्छ।',
        'array'   => ':attribute मा आइटमको संख्या :min र :max को बिचमा हुनुपर्छ।',
    ],
    'boolean'              => ':attribute ठिक अथवा बेठिक हुनुपर्छ।',
    'confirmed'            => ':attribute दाेहाेर्याइएकाे मिलेन।',
    'date'                 => ':attribute को मिति मिलेन।',
    'date_format'          => ':attribute को ढाँचा :format जस्तो हुनुपर्छ।',
    'different'            => ':attribute र :other फरक हुनुपर्छ।',
    'digits'               => ':attribute :digits अंकको हुनुपर्छ।',
    'digits_between'       => ':attribute :min देखि :max अंकको हुनुपर्छ।',
    'dimensions'           => ':attribute को image dimension अमान्य छ।',
    'distinct'             => ':attribute दोहोरिएको value छ।',
    'email'                => ':attribute को इमेल ठेगाना मिलेन।',
    'ends_with'            => ' :attribute तलका मध्ये कुनै एकसँग अन्त्य हुनुपर्छ: :values',
    'exists'               => 'छानिएको :attribute अमान्य छ।',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => ':attribute दिइएको हुनुपर्छ।',
    'image'                => ':attribute मा फोटो हुनुपर्छ।',
    'in'                   => 'छानिएको :attribute अमान्य छ।',
    'in_array'             => ':attribute :other भित्र पर्दैन ।',
    'integer'              => ':attribute पूर्ण संख्या हुनुपर्छ।',
    'ip'                   => ':attribute मा दिइएको मान्य IP ठेगाना हुनुपर्छ।',
    'json'                 => ':attribute मा दिइएको मान्य JSON string हुनुपर्छ।',
    'max'                  => [
        'numeric' => ':attribute :max भन्दा बढी हुनुहुदैन।',
        'file'    => ':attribute :max kilobytes भन्दा बढी हुनुहुदैन।',
        'string'  => ':attribute :max वर्ण भन्दा बढी हुनुहुदैन।',
        'array'   => ':attribute मा :max भन्दा बढी आइटम हुनुहुदैन।',
    ],
    'mimes'                => ':attribute :values प्रकारको फाइल हुनुपर्छ।',
    'mimetypes'            => ':attribute :values प्रकारको फाइल हुनुपर्छ।',
    'min'                  => [
        'numeric' => ':attribute कम्तिमा :min हुनुपर्छ।',
        'file'    => ':attribute कम्तिमा :min kilobytesको हुनुपर्छ।',
        'string'  => ':attribute कम्तिमा :min वर्णको हुनुपर्छ।',
        'array'   => ':attribute मा कम्तिमा :min आइटम हुनुपर्छ।',
    ],
    'not_in'               => 'छानिएको :attribute अमान्य छ।',
    'numeric'              => ':attribute संख्या हुनुपर्छ।',
    'present'              => 'The :attribute field must be present.',
    'regex'                => ':attribute को ढाँचा मिलेन।',
    'required'             => ':attribute दिइएको हुनुपर्छ।',
    'required_if'          => ':attribute चाहिन्छ जब :other :value हुन्छ।',
    'required_unless'      => ':other :values मा नभएसम्म :attribute चाहिन्छ।',
    'required_with'        => ':values भएसम्म :attribute चाहिन्छ।',
    'required_with_all'    => ':values भएसम्म :attribute चाहिन्छ।',
    'required_without'     => ':values नभएको बेला :attribute चाहिन्छ।',
    'required_without_all' => 'कुनैपनि :values नभएको बेला :attribute चाहिन्छ।',
    'same'                 => ':attribute र :other मिल्नुपर्छ।',
    'size'                 => [
        'numeric' => ':attribute :size हुनुपर्छ।',
        'file'    => ':attribute :size kilobytesको हुनुपर्छ।',
        'string'  => ':attribute :size वर्णको हुनुपर्छ।.',
        'array'   => ':attribute मा :size आइटम हुनुपर्छ।',
    ],
    'string'               => ':attribute string हुनुपर्छ।',
    'timezone'             => ':attribute मान्य समय क्षेत्र हुनुपर्छ।',
    'unique'               => 'यो :attribute पहिले नै लिई सकेको छ।',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => ':attribute को ढांचा मिलेन।',

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
            'rule-name'             => 'संदेश',
        ],
        'invalid_currency'      => ':attribute कोड अमान्य छ।',
        'invalid_amount'        => 'रकम :attribute अमान्य छ।',
        'invalid_extension'     => 'फाइलको एक्सटेन्सन अमान्य छ |',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
