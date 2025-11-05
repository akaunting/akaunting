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
    'accepted_if'          => ':other :value হলে :attribute গ্রহণ করা আবশ্যক।',
    'active_url'           => ':attribute একটি বৈধ URL নয়।',
    'after'                => ':attribute অবশ্যই :date এর পরের তারিখ হতে হবে।',
    'after_or_equal'       => ':attribute অবশ্যই :date তারিখের সমান বা পরবর্তী হতে হবে।',
    'alpha'                => ':attribute কেবলমাত্র বর্ণ থাকতে পারে।',
    'alpha_dash'           => ':attribute কেবলমাত্র বর্ণ, সংখ্যা এবং ড্যাশ থাকতে পারে।',
    'alpha_num'            => ':attribute কেবলমাত্র বর্ণ ও সংখ্যা থাকতে পারে।',
    'array'                => ':attribute একটি অ্যারে হতে হবে।',
    'before'               => ':attribute অবশ্যই :date তারিখের আগে হতে হবে।',
    'before_or_equal'      => ':attribute অবশ্যই :date তারিখের সমান বা আগের হতে হবে।',
    'between'              => [
        'array'   => ':attribute-এ অবশ্যই :min থেকে :max টি আইটেম থাকতে হবে।',
        'file'    => ':attribute অবশ্যই :min থেকে :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute অবশ্যই :min এবং :max-এর মধ্যে হতে হবে।',
        'string'  => ':attribute অবশ্যই :min এবং :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean'              => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed'            => ':attribute নিশ্চিতকরণের সাথে মেলেনি।',
    'current_password'     => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়।',
    'date'                 => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals'          => ':attribute অবশ্যই :date তারিখের সমান হতে হবে।',
    'date_format'          => ':attribute, :format বিন্যাসের সাথে মেলে না।',
    'declined'             => ':attribute প্রত্যাখ্যাত হতে হবে।',
    'declined_if'          => ':other :value হলে :attribute প্রত্যাখ্যাত হতে হবে।',
    'different'            => ':attribute এবং :other অবশ্যই <strong>এক নয়</strong> হতে হবে।',
    'digits'               => ':attribute অবশ্যই :digits অঙ্কের হতে হবে।',
    'digits_between'       => ':attribute অবশ্যই :min এবং :max অঙ্কের মধ্যে হতে হবে।',
    'dimensions'           => ':attribute-র ছবির মাপ অবৈধ।',
    'distinct'             => ':attribute ক্ষেত্রে মানটি পুনরাবৃত্ত হয়েছে।',
    'doesnt_start_with'    => ':attribute নিম্নলিখিতগুলোর কোনো একটি দিয়ে শুরু হতে পারবে না: :values।',
    'double'               => ':attribute অবশ্যই বৈধ দশমিক মান হতে হবে।',
    'email'                => ':attribute অবশ্যই একটি বৈধ <strong>ই-মেইল ঠিকানা</strong> হতে হবে।',
    'ends_with'            => ':attribute অবশ্যই নিম্নলিখিতগুলোর কোনও একটি দিয়ে শেষ হতে হবে: :values।',
    'enum'                 => 'নির্বাচিত :attribute অবৈধ।',
    'exists'               => 'নির্বাচিত :attribute অবৈধ।',
    'file'                 => ':attribute অবশ্যই একটি <strong>ফাইল</strong> হতে হবে।',
    'filled'               => ':attribute ক্ষেত্রে অবশ্যই একটি <strong>মান</strong> থাকতে হবে।',
    'gt' => [
        'array'   => ':attribute-এ অবশ্যই :valueটির বেশি আইটেম থাকতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে বড় হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের চেয়ে বড় হতে হবে।',
    ],
    'gte' => [
        'array'   => ':attribute-এ অবশ্যই কমপক্ষে :value টি আইটেম থাকতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের সমান বা বড় হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর সমান বা বড় হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের সমান বা বড় হতে হবে।',
    ],
    'image'                => ':attribute অবশ্যই একটি <strong>ছবি</strong> হতে হবে।',
    'in'                   => 'নির্বাচিত :attribute অবৈধ।',
    'in_array'             => ':attribute ক্ষেত্রটি :other-এ পাওয়া যায়নি।',
    'integer'              => ':attribute অবশ্যই একটি <strong>পূর্ণসংখ্যা</strong> হতে হবে।',
    'ip'                   => ':attribute অবশ্যই একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4'                 => ':attribute অবশ্যই একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6'                 => ':attribute অবশ্যই একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json'                 => ':attribute অবশ্যই একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lt' => [
        'array'   => ':attribute-এ অবশ্যই :valueটির কম আইটেম থাকতে হবে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের চেয়ে ছোট হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর চেয়ে ছোট হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের চেয়ে ছোট হতে হবে।',
    ],
    'lte' => [
        'array'   => ':attribute-এ সর্বোচ্চ :value টি আইটেম থাকতে পারে।',
        'file'    => ':attribute অবশ্যই :value কিলোবাইটের সমান বা ছোট হতে হবে।',
        'numeric' => ':attribute অবশ্যই :value এর সমান বা ছোট হতে হবে।',
        'string'  => ':attribute অবশ্যই :value অক্ষরের সমান বা ছোট হতে হবে।',
    ],
    'mac_address'          => ':attribute অবশ্যই একটি বৈধ MAC ঠিকানা হতে হবে।',
    'max'                  => [
        'array'   => ':attribute-এ :maxটির বেশি আইটেম থাকতে পারবে না।',
        'file'    => ':attribute :max কিলোবাইটের চেয়ে বড় হতে পারবে না।',
        'numeric' => ':attribute :max এর চেয়ে বড় হতে পারবে না।',
        'string'  => ':attribute :max অক্ষরের চেয়ে বড় হতে পারবে না।',
    ],
    'mimes'                => ':attribute অবশ্যই :values টাইপের ফাইল হতে হবে।',
    'mimetypes'            => ':attribute অবশ্যই :values টাইপের ফাইল হতে হবে।',
    'min'                  => [
        'array'   => ':attribute-এ অন্তত :min টি আইটেম থাকতে হবে।',
        'file'    => ':attribute অন্তত :min কিলোবাইটের হতে হবে।',
        'numeric' => ':attribute অন্তত :min হতে হবে।',
        'string'  => ':attribute অন্তত :min অক্ষরের হতে হবে।',
    ],
    'multiple_of'          => ':attribute অবশ্যই :value এর গুণিতক হতে হবে।',
    'not_in'               => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex'            => ':attribute বিন্যাস অবৈধ।',
    'numeric'              => ':attribute অবশ্যই একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters'       => ':attribute-এ অন্তত একটি বর্ণ থাকতে হবে।',
        'mixed'         => ':attribute-এ অন্তত একটি বড় হাতের এবং একটি ছোট হাতের বর্ণ থাকতে হবে।',
        'numbers'       => ':attribute-এ অন্তত একটি সংখ্যা থাকতে হবে।',
        'symbols'       => ':attribute-এ অন্তত একটি বিশেষ চিহ্ন থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি তথ্য ফাঁসে পাওয়া গেছে। অনুগ্রহ করে অন্য :attribute নির্বাচন করুন।',
    ],
    'present'              => ':attribute ক্ষেত্রটি <strong>উপস্থিত</strong> থাকতে হবে।',
    'prohibited'           => ':attribute নিষিদ্ধ।',
    'prohibited_if'        => ':other :value হলে :attribute নিষিদ্ধ।',
    'prohibited_unless'    => ':other :values এর মধ্যে না থাকলে :attribute নিষিদ্ধ।',
    'prohibits'            => ':attribute ক্ষেত্র :other-কে উপস্থিত থাকতে বাধা দেয়।',
    'regex'                => ':attribute বিন্যাস <strong>অবৈধ</strong>।',
    'required'             => ':attribute ক্ষেত্রটি <strong>আবশ্যক</strong>।',
    'required_array_keys'  => ':attribute ক্ষেত্রটিতে অবশ্যই নিম্নলিখিত কীগুলো থাকতে হবে: :values।',
    'required_if'          => ':other :value হলে :attribute ক্ষেত্রটি আবশ্যক।',
    'required_unless'      => ':other :values এর মধ্যে না থাকলে :attribute ক্ষেত্রটি আবশ্যক।',
    'required_with'        => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি আবশ্যক।',
    'required_with_all'    => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি আবশ্যক।',
    'required_without'     => ':values অনুপস্থিত থাকলে :attribute ক্ষেত্রটি আবশ্যক।',
    'required_without_all' => ':values এর কোনোটি উপস্থিত না থাকলে :attribute ক্ষেত্রটি আবশ্যক।',
    'same'                 => ':attribute এবং :other অবশ্যই এক হতে হবে।',
    'size'                 => [
        'array'   => ':attribute-এ অবশ্যই :size টি আইটেম থাকতে হবে।',
        'file'    => ':attribute অবশ্যই :size কিলোবাইটের হতে হবে।',
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'string'  => ':attribute অবশ্যই <strong>:size অক্ষরের</strong> হতে হবে।',
    ],
    'starts_with'          => ':attribute অবশ্যই নিম্নলিখিতগুলোর কোনো একটি দিয়ে শুরু হতে হবে: :values।',
    'string'               => ':attribute অবশ্যই একটি <strong>স্ট্রিং</strong> হতে হবে।',
    'timezone'             => ':attribute একটি বৈধ অঞ্চল হতে হবে।',
    'unique'               => ':attribute ইতোমধ্যে <strong>ব্যবহৃত</strong> হয়েছে।',
    'uploaded'             => ':attribute আপলোড <strong>ব্যর্থ</strong> হয়েছে।',
    'url'                  => ':attribute বিন্যাস <strong>অবৈধ</strong>।',
    'uuid'                 => ':attribute অবশ্যই একটি বৈধ UUID হতে হবে।',

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
            'rule-name' => 'custom-message',
        ],
        'invalid_currency'       => ':attribute কোডটি অবৈধ।',
        'invalid_amount'         => ':attribute পরিমাণটি অবৈধ।',
        'invalid_extension'      => 'ফাইল এক্সটেনশন অবৈধ।',
        'invalid_dimension'      => ':attribute এর মাপ সর্বোচ্চ :width x :height পিক্সেল হতে পারবে।',
        'invalid_colour'         => ':attribute রঙটি অবৈধ।',
        'invalid_payment_method' => 'পেমেন্ট পদ্ধতিটি অবৈধ।',
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
