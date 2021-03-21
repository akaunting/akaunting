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

    'accepted'             => 'Трябва да приемете :attribute.',
    'active_url'           => 'Полето :attribute не е валиден URL адрес.',
    'after'                => 'Полето :attribute трябва да бъде дата след :date.',
    'after_or_equal'       => 'Полето :attribute трябва да бъде дата след или равна на :date.',
    'alpha'                => 'Полето :attribute трябва да съдържа само букви.',
    'alpha_dash'           => 'Полето :attribute трябва да съдържа само букви, цифри, долна черта и тире.',
    'alpha_num'            => 'Полето :attribute трябва да съдържа само букви и цифри.',
    'array'                => 'Полето :attribute трябва да бъде масив.',
    'before'               => 'Полето :attribute трябва да бъде дата преди :date.',
    'before_or_equal'      => 'Полето :attribute трябва да бъде дата преди или равна на :date.',
    'between'              => [
        'numeric' => 'Полето :attribute трябва да бъде между :min и :max.',
        'file'    => 'Полето :attribute трябва да бъде между :min и :max килобайта.',
        'string'  => 'Полето :attribute трябва да бъде между :min и :max знака.',
        'array'   => 'Полето :attribute трябва да има между :min - :max елемента.',
    ],
    'boolean'              => 'Полето :attribute трябва да съдържа Да или Не',
    'confirmed'            => 'Полето :attribute не е потвърдено.',
    'date'                 => 'Полето :attribute не е валидна дата.',
    'date_format'          => 'Полето :attribute не е във формат :format.',
    'different'            => ':attribute и :other трябва да са <strong>различни</strong>.',
    'digits'               => 'Полето :attribute трябва да има :digits цифри.',
    'digits_between'       => 'Полето :attribute трябва да има между :min и :max цифри.',
    'dimensions'           => 'Невалидни размери за снимка :attribute.',
    'distinct'             => 'Данните в полето :attribute се дублират.',
    'email'                => ':attribute трябва да е валиден <strong>имейл адрес</strong>.',
    'ends_with'            => ':attribute трябва да завършва на един от следните: :values',
    'exists'               => 'Избранато поле :attribute вече съществува.',
    'file'                 => ':attribute трябва да е <strong>файл</strong>.',
    'filled'               => ':attribute трябва да има <strong>стойност</strong>.',
    'image'                => ':attribute трябва да е <strong>снимка</strong>.',
    'in'                   => 'Избраното поле :attribute е невалидно.',
    'in_array'             => 'Полето :attribute не съществува в :other.',
    'integer'              => ':attribute трябва да е <strong>число</strong>.',
    'ip'                   => 'Полето :attribute трябва да бъде IP адрес.',
    'json'                 => 'Полето :attribute трябва да бъде JSON низ.',
    'max'                  => [
        'numeric' => 'Полето :attribute трябва да бъде по-малко от :max.',
        'file'    => 'Полето :attribute трябва да бъде по-малко от :max килобайта.',
        'string'  => 'Полето :attribute трябва да бъде по-малко от :max знака.',
        'array'   => 'Полето :attribute трябва да има по-малко от :max елемента.',
    ],
    'mimes'                => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'mimetypes'            => 'Полето :attribute трябва да бъде файл от тип: :values.',
    'min'                  => [
        'numeric' => 'Полето :attribute трябва да бъде минимум :min.',
        'file'    => 'Полето :attribute трябва да бъде минимум :min килобайта.',
        'string'  => 'Полето :attribute трябва да бъде минимум :min знака.',
        'array'   => 'Полето :attribute трябва има минимум :min елемента.',
    ],
    'not_in'               => 'Избраното поле :attribute е невалидно.',
    'numeric'              => 'Полето :attribute трябва да бъде число.',
    'present'              => ':attribute трябва да <strong>съществува</strong>.',
    'regex'                => 'Форматът на :attribute и <strong>невалиден</strong>.',
    'required'             => 'Полето на :attribute е <strong>задължително</strong>.',
    'required_if'          => 'Полето :attribute се изисква, когато :other е :value.',
    'required_unless'      => 'Полето :attribute е задължително освен ако :other е в :values.',
    'required_with'        => 'Полето :attribute се изисква, когато :values има стойност.',
    'required_with_all'    => 'Полето :attribute е задължително, когато :values имат стойност.',
    'required_without'     => 'Полето :attribute се изисква, когато :values няма стойност.',
    'required_without_all' => 'Полето :attribute се изисква, когато никое от полетата :values няма стойност.',
    'same'                 => 'Полетата :attribute и :other трябва да съвпадат.',
    'size'                 => [
        'numeric' => 'Полето :attribute трябва да бъде :size.',
        'file'    => 'Полето :attribute трябва да бъде :size килобайта.',
        'string'  => ':attribute трябва да е <strong>:size знаци</strong>.',
        'array'   => 'Полето :attribute трябва да има :size елемента.',
    ],
    'string'               => ':attribute трябва да е <strong>дума</strong>.',
    'timezone'             => 'Полето :attribute трябва да съдържа валидна часова зона.',
    'unique'               => ':attribute е вече <strong>зает</strong>.',
    'uploaded'             => ':attribute <strong>не успя</strong> да се качи.',
    'url'                  => 'Форматът на :attribute не е <strong>валиден</strong>.',

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
            'rule-name'             => 'custom-message',
        ],
        'invalid_currency'      => ':attribute код е невалиден.',
        'invalid_amount'        => 'Сумата :attribute е невалидна.',
        'invalid_extension'     => 'Това файлово разширение е невалидно.',
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
