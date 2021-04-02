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

    'accepted'             => ':attribute গ্রহণ করা আবশ্যক।',
    'active_url'           => 'এই :attribute একটি বৈধ URL নয়।',
    'after'                => ':date অবশ্যই :attribute এর পরের একটি তারিখ হতে হবে।',
    'after_or_equal'       => ':attribute টি অবশ্যই :date এর সাথে মিল অথবা এর পরের একটি তারিখ হতে হবে।',
    'alpha'                => ':attribute শুধুমাত্র অক্ষর থাকতে পারে।',
    'alpha_dash'           => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, এবং ড্যাশ থাকতে পারে।',
    'alpha_num'            => ':attribute শুধুমাত্র বর্ণ ও সংখ্যা থাকতে পারে।',
    'array'                => ':attribute একটি অ্যারে হতে হবে।',
    'before'               => ':date অবশ্যই :attribute এর আগের একটি তারিখ হতে হবে।',
    'before_or_equal'      => ':attribute টি অবশ্যই :date এর সাথে মিল অথবা এর আগের একটি তারিখ হতে হবে।',
    'between'              => [
        'numeric' => ':min এবং :max :attribute মধ্যে হতে হবে।',
        'file'    => ':min এবং :max কিলোবাইট :attribute মধ্যে হতে হবে।',
        'string'  => ':min এবং :max অক্ষর :attribute মধ্যে হতে হবে।',
        'array'   => ':min এবং :max আইটেম :attribute মধ্যে হতে হবে।',
    ],
    'boolean'              => ':attribute স্থানে  সত্য বা মিথ্যা হতে হবে।',
    'confirmed'            => ':attribute নিশ্চিতকরণ এর  সাথে মিলছে না।',
    'date'                 => ':attribute একটি বৈধ তারিখ নয়।',
    'date_format'          => ':attribute, :format এর সাথে বিন্যাস মিলছে না।',
    'different'            => ':attribute এবং :other আলাদা হতে হবে।',
    'digits'               => ':attribute :digits অবশ্যই একটি সংখ্যার ডিজিট হতে হবে।',
    'digits_between'       => ':attribute অবশ্যই :min এবং :max ডিজিট এর মধ্যে হতে হবে।',
    'dimensions'           => ':attribute অবৈধ ইমেজ মাত্রা রয়েছে।',
    'distinct'             => ':attribute এর স্থানে একটি নকল মান আছে।',
    'email'                => ':attribute একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with'            => '
নিম্নলিখিত :attribute অবশ্যই নিম্নলিখিতগুলির একটির সাথে শেষ হতে হবে :value',
    'exists'               => 'নির্বাচিত :attribute টি অবৈধ।',
    'file'                 => ':attribute একটি বৈধ ফাইল হতে হবে।',
    'filled'               => ':attribute একটি বৈধ মান হতে হবে।',
    'image'                => ':attribute একটি বৈধ ছবি হতে হবে।',
    'in'                   => 'নির্বাচিত :attribute টি অবৈধ।',
    'in_array'             => ':attribute উপাদানটি :other এ খুঁজে পাওয়া যায়নি।.',
    'integer'              => ':attribute একটি বৈধ পূর্ণসংখ্যা হতে হবে।',
    'ip'                   => ':attribute একটি বৈধ  IP address হতে হবে।',
    'json'                 => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'max'                  => [
        'numeric' => ' :attribute এর মান :max এর চেয়ে বড় হতে পারেনা।',
        'file'    => ':attribute এর মান :max কিলোবাইট এর চেয়ে বড় হতে পারেনা।',
        'string'  => ':attribute এর মান :max অক্ষর এর চেয়ে বড় হতে পারেনা।',
        'array'   => ':attribute এর উপাদান সংখ্যা :max চেয়ে বড় হতে পারবেনা।',
    ],
    'mimes'                => ':attribute এর একটি ফাইল হতে হবে: :values।',
    'mimetypes'            => ':attribute এর একটি ফাইল হতে হবে: :values।',
    'min'                  => [
        'numeric' => ':attribute অবশ্যই :min এর চেয়ে ছোট হতে হবে।',
        'file'    => ':attribute অবশ্যই :min কিলোবাইট এর চেয়ে ছোট হতে হবে।',
        'string'  => ':attribute অবশ্যই অন্তত :min অক্ষরের হতে হবে।',
        'array'   => ':attribute-এ অবশ্যই অন্তত :min উপাদান থাকতে হতে হবে।',
    ],
    'not_in'               => 'নির্বাচিত :attribute অবৈধ।',
    'numeric'              => ':attribute একটি সংখ্যা হতে হবে।',
    'present'              => ':attribute একটি  মান থাকতে হবে।',
    'regex'                => ':attribute টি অকার্যকর ।',
    'required'             => ':attribute টি প্রয়োজনীয়।',
    'required_if'          => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যেখানে :other হল :value।',
    'required_unless'      => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যদি না :other, :value তে উপস্থিত থাকে।',
    'required_with'        => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যখন  :values উপস্থিত।',
    'required_with_all'    => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যখন :values উপস্থিত।',
    'required_without'     => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যখন :values অনুপস্থিত।',
    'required_without_all' => ':attribute স্থানটি পূরণ করা বাধ্যতামূলক যখন সকল :values অনুপস্থিত।',
    'same'                 => ':attribute এবং :other অবশ্যই মিলতে হবে।',
    'size'                 => [
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'file'    => ':attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'string'  => ' :attribute টি অবশ্যই  <strong>:size  সংখ্যক বর্ণের</strong> হতে হবে।',
        'array'   => ':attribute অবশ্যই :size আইটেম হতে হবে।',
    ],
    'string'               => ' :attribute টি  অবশ্যই <strong>স্ট্রিং</strong> হতে হবে।',
    'timezone'             => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique'               => ':attribute টি ইতোমধ্যেই  <strong> গৃহীত </strong> হয়েছে',
    'uploaded'             => ' :attribute  টি  আপলোড হতে <strong>  ব্যর্থ </strong> ।',
    'url'                  => ' :attribute ফর্ম্যাট টি <strong> সঠিক নয় </strong> ।',

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
            'rule-name'             => 'পছন্দসই বার্তা',
        ],
        'invalid_currency'      => ' :attribute  কোড টি বৈধ নয়।',
        'invalid_amount'        => 'পরিমাণ  :attribute বৈশিষ্ট্যটি বৈধ নয়।',
        'invalid_extension'     => 'ফাইল এক্সটেনসনটি বৈধ নয়।',
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
