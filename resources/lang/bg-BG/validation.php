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

    'accepted' => 'Трябва да приемете :attribute.',
    'accepted_if' => 'Полето :attribute се изисква, когато :other е :value.',
    'active_url' => 'Полето :attribute не е валиден URL адрес.',
    'after' => 'Полето :attribute трябва да бъде дата след :date.',
    'after_or_equal' => 'Полето :attribute трябва да бъде дата след или равна на :date.',
    'alpha' => 'Полето :attribute трябва да съдържа само букви.',
    'alpha_dash' => 'Полето :attribute трябва да съдържа само букви, цифри, долна черта и тире.',
    'alpha_num' => 'Полето :attribute трябва да съдържа само букви и цифри.',
    'array' => 'Полето :attribute трябва да бъде масив.',
    'before' => 'Полето :attribute трябва да бъде дата преди :date.',
    'before_or_equal' => 'Полето :attribute трябва да бъде дата преди или равна на :date.',
    'between' => [
        'array' => 'Полето :attribute трябва да има между :min - :max елемента.',
        'file' => 'Полето :attribute трябва да бъде между :min и :max килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде между :min и :max.',
        'string' => 'Полето :attribute трябва да бъде между :min и :max знака.',
    ],
    'boolean' => 'Полето :attribute трябва да съдържа Да или Не',
    'confirmed' => 'Полето :attribute не е потвърдено.',
    'current_password' => 'Грешна парола.',
    'date' => 'Полето :attribute не е валидна дата.',
    'date_equals' => 'Полето :attribute трябва да бъде дата равна на :date.',
    'date_format' => 'Полето :attribute не е във формат :format.',
    'declined' => 'Трябва да откажете :attribute.',
    'declined_if' => 'Полето :attribute трябва да бъде отказано, когато :other е :value.',
    'different' => ':attribute и :other трябва да са <strong>различни</strong>.',
    'digits' => 'Полето :attribute трябва да има :digits цифри.',
    'digits_between' => 'Полето :attribute трябва да има между :min и :max цифри.',
    'dimensions' => 'Невалидни размери за снимка :attribute.',
    'distinct' => 'Данните в полето :attribute се дублират.',
    'doesnt_start_with' => ':attribute не трябва да започва с един от следните: :values',
    'email' => ':attribute трябва да е валиден <strong>имейл адрес</strong>.',
    'ends_with' => ':attribute трябва да завършва на един от следните: :values',
    'enum' => 'Избраният :attribute е невалиден.',
    'exists' => 'Избранато поле :attribute вече съществува.',
    'file' => ':attribute трябва да е <strong>файл</strong>.',
    'filled' => ':attribute трябва да има <strong>стойност</strong>.',
    'gt' => [
        'array' => ':attribute трябва да има повече от :value продукта.',
        'file' => ':attribute трябва да е по-голям от :value килобайта.',
        'numeric' => ':attribute трябва да е по-голям от :value.',
        'string' => ':attribute трябва да е по-голямо от :value знака.',
    ],
    'gte' => [
        'array' => ':attribute трябва да има :value продукта или повече.',
        'file' => ':attribute трябва да е по-голям или равен на :value килобайта.',
        'numeric' => ':attribute трябва да е по-голям или равен на :value.',
        'string' => ':attribute трябва да е по-голямо или равно на :value знака.',
    ],
    'image' => ':attribute трябва да е <strong>снимка</strong>.',
    'in' => 'Избраното поле :attribute е невалидно.',
    'in_array' => 'Полето :attribute не съществува в :other.',
    'integer' => ':attribute трябва да е <strong>число</strong>.',
    'ip' => 'Полето :attribute трябва да бъде IP адрес.',
    'ipv4' => ':attribute трябва да е валиден IPv4 адрес.',
    'ipv6' => ':attribute трябва да е валиден IPv6 адрес.',
    'json' => 'Полето :attribute трябва да бъде JSON низ.',
    'lt' => [
        'array' => ':attribute трябва да има по-малко от :value продукта.',
        'file' => ':attribute трябва да е по-малко от :value килобайта.',
        'numeric' => ':attribute трябва да е по-малко от :value.',
        'string' => ':attribute трябва да е по-малко от :value знака.',
    ],
    'lte' => [
        'array' => ':attribute не трябва да има повече от :value продукта.',
        'file' => ':attribute трябва да е по-малък или равен на :value килобайта.',
        'numeric' => ':attribute трябва да е по-малък или равен на :value.',
        'string' => ':attribute трябва да е по-малко или равно на :value знака.',
    ],
    'mac_address' => ':attribute трябва да е валиден MAC адрес.',
    'max' => [
        'array' => 'Полето :attribute трябва да има по-малко от :max елемента.',
        'file' => 'Полето :attribute трябва да бъде по-малко от :max килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде по-малко от :max.',
        'string' => 'Полето :attribute трябва да бъде по-малко от :max знака.',
    ],
    'mimes' => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'mimetypes' => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'min' => [
        'array' => 'Полето :attribute трябва има минимум :min елемента.',
        'file' => 'Полето :attribute трябва да бъде минимум :min килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде минимум :min.',
        'string' => 'Полето :attribute трябва да бъде минимум :min знака.',
    ],
    'multiple_of' => ':attribute трябва да е кратно на :value.',
    'not_in' => 'Избраното поле :attribute е невалидно.',
    'not_regex' => 'Форматът на :attribute е невалиден.',
    'numeric' => 'Полето :attribute трябва да бъде число.',
    'password' => [
        'letters' => ':attribute трябва да съдържа поне една буква.',
        'mixed' => ':attribute трябва да съдържа поне една главна и една малка буква.',
        'numbers' => ':attribute трябва да съдържа поне една цифра.',
        'symbols' => ':attribute трябва да съдържа поне един символ.',
        'uncompromised' => 'Даденият :attribute се появи при изтичане на данни. Моля, изберете различен :attribute.',
    ],
    'present' => ':attribute трябва да <strong>съществува</strong>.',
    'prohibited' => 'Полето :attribute е забранено.',
    'prohibited_if' => 'Полето :attribute е забранено, когато :other е :value.',
    'prohibited_unless' => 'Полето :attribute е забранено, освен ако :other е в :values.',
    'prohibits' => 'Полето :attribute забранява присъствието на :other.',
    'regex' => 'Форматът на :attribute и <strong>невалиден</strong>.',
    'required' => 'Полето на :attribute е <strong>задължително</strong>.',
    'required_array_keys' => 'Полето :attribute трябва да съдържа записи за: :values.',
    'required_if' => 'Полето :attribute се изисква, когато :other е :value.',
    'required_unless' => 'Полето :attribute е задължително освен ако :other е в :values.',
    'required_with' => 'Полето :attribute се изисква, когато :values има стойност.',
    'required_with_all' => 'Полето :attribute е задължително, когато :values имат стойност.',
    'required_without' => 'Полето :attribute се изисква, когато :values няма стойност.',
    'required_without_all' => 'Полето :attribute се изисква, когато никое от полетата :values няма стойност.',
    'same' => 'Полетата :attribute и :other трябва да съвпадат.',
    'size' => [
        'array' => 'Полето :attribute трябва да има :size елемента.',
        'file' => 'Полето :attribute трябва да бъде :size килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде :size.',
        'string' => ':attribute трябва да е <strong>:size знаци</strong>.',
    ],
    'starts_with' => ':attribute трябва да започва с един от следните: :values.',
    'string' => ':attribute трябва да е <strong>дума</strong>.',
    'timezone' => 'Полето :attribute трябва да съдържа валидна часова зона.',
    'unique' => ':attribute е вече <strong>зает</strong>.',
    'uploaded' => ':attribute <strong>не успя</strong> да се качи.',
    'url' => 'Форматът на :attribute не е <strong>валиден</strong>.',
    'uuid' => ':attribute трябва да е валиден UUID.',

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
        'invalid_currency'      => ':attribute код е невалиден.',
        'invalid_amount'        => 'Сумата :attribute е невалидна.',
        'invalid_extension'     => 'Това файлово разширение е невалидно.',
        'invalid_dimension'     => 'Размерите :attribute трябва да бъдат макс. :width x :height px.',
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
