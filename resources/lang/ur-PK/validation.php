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

    'accepted'             => ':attribute تسلیم کرنا لازمی ہے۔',
    'active_url'           => ':attribute قابلِ قبول یو آر ایل نہیں ہے۔',
    'after'                => ':attribute لازماً :date کے بعد کی کوئی تاریخ ہو۔',
    'after_or_equal'       => ':attribute لازماً :date کے بعد کی کوئی تاریخ ہو۔',
    'alpha'                => ':attribute صرف حروفِ تہجی پر مشتمل ہو سکتا ہے۔',
    'alpha_dash'           => ':attribute صرف حروفِ تہجی، اعداد، ڈیشِز پر مشتمل ہو سکتا ہے۔',
    'alpha_num'            => ':attribute میں صرف حروفِ تہجی و اعداد شامل ہو سکتے ہیں۔',
    'array'                => ':attribute لازماً کسی رینج پر مشتمل ہو۔',
    'before'               => ':attribute لازماً :date سے پہلے کی کوئی تاریخ ہو۔',
    'before_or_equal'      => ':attribute لازماً :date سے پہلے کی کوئی تاریخ ہو۔',
    'between'              => [
        'numeric' => ':attribute لازماً :min اور :max کے درمیان ہو۔',
        'file'    => ':attribute لازماً :min اور :max کلو بائٹس کے درمیان ہو۔',
        'string'  => ':attribute لازماً :min اور :max کریکٹرز کے درمیان ہو۔',
        'array'   => ':attribute لازماً :min اور :max آئٹمز کے درمیان ہو۔',
    ],
    'boolean'              => ':attribute لازماً درست یا غلط ہونا چاہیے۔',
    'confirmed'            => ':attribute تصدیق سے مطابقت نہیں رکھتا۔',
    'date'                 => ':attribute قابلِ قبول تاریخ نہیں ہے۔',
    'date_format'          => ':attribute فارمیٹ :format کے مطابق نہیں ہے۔',
    'different'            => ':attribute اور :other لازماً مختلف ہوں۔',
    'digits'               => ':attribute لازماً :digits اعداد ہوں۔',
    'digits_between'       => ':attribute لازماً :min اور :max اعداد کے درمیان ہو۔',
    'dimensions'           => ': خصوصیت غلط تصویر طول و عرض ہے.',
    'distinct'             => ':attribute کی دہری ویلیو ہے۔',
    'email'                => ':attribute لازماً قابلِ قبول ای میل ہو۔',
    'exists'               => 'منتخب :attribute درست نہیں ہے۔',
    'file'                 => ':attribute لازماً کوئی فائل ہو۔.',
    'filled'               => ':attribute کو بھرنا ضروری ہے۔',
    'image'                => ':attribute لازماً کوئی تصویر ہو۔',
    'in'                   => 'منتخب :attribute قابلِ قبول نہیں ہے۔',
    'in_array'             => ':attribute فیلڈ :other میں موجود نہیں ہے۔',
    'integer'              => ':attribute لازماً کوئی عدد ہو۔',
    'ip'                   => ':attribute لازماً قابلِ قبول آئی پی پتہ ہو۔',
    'json'                 => ':attribute لازماً قابلِ قبول JSON سٹرِنگ ہو۔',
    'max'                  => [
        'numeric' => ':attribute کو :max سے بڑا نہیں ہونا چاہیے۔',
        'file'    => ':attribute کو :max کلو بائٹس سے زیادہ نہیں ہونا چاہیے۔',
        'string'  => ':attribute کو :max کریکٹرز سے زیادہ نہیں ہونا چاہیے۔',
        'array'   => ':attribute میں :max سے زیادہ آئٹمز نہیں ہو سکتیں۔',
    ],
    'mimes'                => ':attribute لازماً :type :values قسم کی فائل ہو۔',
    'mimetypes'            => ':attribute لازماً :type :values قسم کی فائل ہو۔',
    'min'                  => [
        'numeric' => ':attribute لازماً کم از کم :min ہو۔',
        'file'    => ':attribute لازماً کم از کم :min کلو بائٹس کی ہو۔',
        'string'  => ':attribute لازماً کم از کم :min کریکٹرز طویل ہو۔',
        'array'   => ':attribute میں لازماً کم از کم :min آئٹمز ہوں۔',
    ],
    'not_in'               => 'منتخب :attribute قابلِ قبول نہیں ہے۔',
    'numeric'              => ':attribute لازماً کوئی عدد ہو۔',
    'present'              => ':attribute فیلڈ موجود ہونا ضروری ہے۔',
    'regex'                => ':attribute قابلِ قبول فارمیٹ میں نہیں ہے۔',
    'required'             => ':attribute فیلڈ درکار ہے۔',
    'required_if'          => ':attribute درکار ہے اگر :other کی ویلیو :value ہو۔',
    'required_unless'      => 'جب تک :other :values میں نہ ہو تو :attribute فیلڈ درکار ہے۔',
    'required_with'        => ':attribute فیلڈ درکار ہے اگر :values موجود ہوں۔',
    'required_with_all'    => ':attribute فیلڈ درکار ہے اگر :values موجود ہوں۔',
    'required_without'     => ':attribute درکار ہے جب :values موجود ہو۔',
    'required_without_all' => ':attribute فیلڈ درکار ہے جب :values میں سے کوئی بھی موجود نہ ہو۔',
    'same'                 => ':attribute اور :other لازماً ایک دوسرے سے مماثل ہوں۔',
    'size'                 => [
        'numeric' => ':attribute لازماً :size ہوں۔',
        'file'    => ':attribute کا سائز لازماً :size کلو بائٹس ہو۔',
        'string'  => ':attribute لازماً :size کریکٹرز پر مشتمل ہو۔',
        'array'   => ':attribute میں لازماً :size آئٹمز شامل ہوں۔',
    ],
    'string'               => ':attribute لازماً کوئی سٹرنگ ہو۔',
    'timezone'             => ':attribute لازماً کوئی قابلِ قبول خطۂِ وقت ہو۔',
    'unique'               => ':attribute کو پہلے ہی کسی نے حاصل کر لیا ہے۔',
    'uploaded'             => 'خصوصیت اپ لوڈ کرنے میں ناکامی.',
    'url'                  => ':attribute فارمیٹ قابلِ قبول نہیں ہے۔',

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
            'rule-name' => 'مخصوص پیغام',
        ],
        'invalid_currency' => ':attribute فارمیٹ قابلِ قبول نہیں ہے۔',
        'invalid_amount'   => 'منتخب :attribute قابلِ قبول نہیں ہے۔',
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
