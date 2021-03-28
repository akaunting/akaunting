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

    'accepted'             => 'Полето :attribute мора да биде прифатено.',
    'active_url'           => 'Полето :attribute не е валиден URL.',
    'after'                => 'Полето :attribute мора да биде датум после :date.',
    'after_or_equal'       => ':attribute мора да биде датум после или на :date.',
    'alpha'                => 'Полето :attribute може да содржи само букви.',
    'alpha_dash'           => 'Полето :attribute може да содржи само букви, цифри, долна црта и тире.',
    'alpha_num'            => 'Полето :attribute може да содржи само букви и цифри.',
    'array'                => 'Полето :attribute мора да биде низа.',
    'before'               => 'Полето :attribute мора да биде датум пред :date.',
    'before_or_equal'      => ':attribute мора да биде датум пред или на :date.',
    'between'              => [
        'numeric' => 'Полето :attribute мора да биде помеѓу :min и :max.',
        'file'    => 'Полето :attribute мора да биде помеѓу :min и :max килобајти.',
        'string'  => 'Полето :attribute мора да биде помеѓу :min и :max карактери.',
        'array'   => 'Полето :attribute мора да има помеѓу :min - :max карактери.',
    ],
    'boolean'              => 'The :attribute field must be true or false',
    'confirmed'            => 'Полето :attribute не е потврдено.',
    'date'                 => 'Полето :attribute не е валиден датум.',
    'date_format'          => 'Полето :attribute не е во формат :format.',
    'different'            => 'Полињата :attribute и :other треба да се различни.',
    'digits'               => 'Полето :attribute треба да има :digits цифри.',
    'digits_between'       => 'Полето :attribute треба да има помеѓу :min и :max цифри.',
    'dimensions'           => ':attribute има неважечки димензии на сликата.',
    'distinct'             => 'Полето :attribute има дупликат вредност.',
    'email'                => 'Полето :attribute не е во валиден формат.',
    'ends_with'            => 'Атрибутот :attribute мора да завршува со следниве вредности: :values',
    'exists'               => 'Избранато поле :attribute веќе постои.',
    'file'                 => ':attribute мора да биде датотека.',
    'filled'               => 'Полето :attribute е задолжително.',
    'image'                => 'Полето :attribute мора да биде слика.',
    'in'                   => 'Избраното поле :attribute е невалидно.',
    'in_array'             => 'Полето :attribute не постои во :other.',
    'integer'              => 'Полето :attribute мора да биде цел број.',
    'ip'                   => 'Полето :attribute мора да биде IP адреса.',
    'json'                 => ':attribute мора да биде валиден JSON стринг.',
    'max'                  => [
        'numeric' => 'Полето :attribute мора да биде помало од :max.',
        'file'    => 'Полето :attribute мора да биде помало од :max килобајти.',
        'string'  => 'Полето :attribute мора да има помалку од :max карактери.',
        'array'   => 'Полето :attribute не може да има повеќе од :max карактери.',
    ],
    'mimes'                => 'Полето :attribute мора да биде фајл од типот: :values.',
    'mimetypes'            => 'Полето :attribute мора да биде фајл од типот: :values.',
    'min'                  => [
        'numeric' => 'Полето :attribute мора да биде минимум :min.',
        'file'    => 'Полето :attribute мора да биде минимум :min килобајти.',
        'string'  => 'Полето :attribute мора да има минимум :min карактери.',
        'array'   => 'Атрибутот :attribute мора да има минимум :min ставки.',
    ],
    'not_in'               => 'Избраното поле :attribute е невалидно.',
    'numeric'              => 'Полето :attribute мора да биде број.',
    'present'              => 'Полето :attribute е задолжително.',
    'regex'                => 'Полето :attribute е во невалиден формат.',
    'required'             => 'Полето :attribute е задолжително.',
    'required_if'          => 'Полето :attribute е задолжително, кога :other е :value.',
    'required_unless'      => 'Полето :attribute е задолжително, освен ако :other е :values.',
    'required_with'        => 'Полето :attribute е задолжително, кога е внесено :values.',
    'required_with_all'    => 'Полето :attribute е задолжително, кога е внесено :values.',
    'required_without'     => 'Полето :attribute е задолжително, кога не е внесено :values.',
    'required_without_all' => 'Полето :attribute е задолжително кога не постои ниту една :values.',
    'same'                 => 'Полињата :attribute и :other треба да совпаѓаат.',
    'size'                 => [
        'numeric' => 'Полето :attribute мора да биде :size.',
        'file'    => 'Полето :attribute мора да биде :size килобајти.',
        'string'  => 'Полето :attribute мора да има :size карактери.',
        'array'   => 'Атрибутот :attribute мора да има :size ставки.',
    ],
    'string'               => ':attribute мора да биде стринг.',
    'timezone'             => ':attribute мора да биде валидна зона.',
    'unique'               => 'Полето :attribute веќе постои.',
    'uploaded'             => ':attribute не е прикачен.',
    'url'                  => 'Полето :attribute не е во валиден формат.',

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
            'rule-name'             => 'Прилагодена порака',
        ],
        'invalid_currency'      => 'Полето :attribute не е во валиден формат.',
        'invalid_amount'        => 'Вредноста на :attribute не е валидна.',
        'invalid_extension'     => 'Екстензијата на датотеката не е валидна.',
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
