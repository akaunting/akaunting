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

    'accepted'             => ':attribute мора бити прихваћен.',
    'active_url'           => ':attribute није валидна УРЛ путања.',
    'after'                => ':attribute мора бити датум након датума :date.',
    'after_or_equal'       => ': атрибут мора бити једнак датум :date или датум након њега.',
    'alpha'                => 'На : атрибут може да садржи само слова.',
    'alpha_dash'           => 'На : атрибут може да садржи само слова, бројеве и цртице.',
    'alpha_num'            => 'На : атрибут може да садржи само слова и бројеве.',
    'array'                => 'На : атрибут мора бити низ.',
    'before'               => ':attribute мора бити датум пре датума :date.',
    'before_or_equal'      => ':attribute мора бити датум пре или исти датуму :date.',
    'between'              => [
        'numeric' => 'На : атрибут мора бити између: мин и: маx.',
        'file'    => 'На : атрибут мора бити између: мин и: маx килобајта.',
        'string'  => ':attribute мора бити између :min и :max карактера.',
        'array'   => ':attribute мора имати између :min и :max ставки.',
    ],
    'boolean'              => 'Поље :attribute мора бити истина(true) или лаж(false).',
    'confirmed'            => ':attribute потврда се не поклапа.',
    'date'                 => ':attribute није валидан датум.',
    'date_format'          => ':attribute се не покалапа са форматом :format.',
    'different'            => ':attribute и :other мора да буде различито.',
    'digits'               => ':attribute мора да буде :digits цифара.',
    'digits_between'       => ':attribute мора да буде између :min и :max цифара.',
    'dimensions'           => ':attribute има неодговарајуће димензије слике.',
    'distinct'             => ':attribute поље има дуплирану вредност.',
    'email'                => ':attribute мора да буде валидна адреса епоште.',
    'exists'               => 'Изабрани :attribute је неважећи.',
    'file'                 => ':attribute мора да буде датотека-фајл.',
    'filled'               => ':attribute поље мора да садржи вредност.',
    'image'                => ':attribute мора да буде слика.',
    'in'                   => 'Изабрани :attribute је неважећи.',
    'in_array'             => ':attribute поље не постоји у :other.',
    'integer'              => ':attribute мора да буде цео број.',
    'ip'                   => ':attribute мора да буде валидна ИП адреса.',
    'json'                 => ':attribute мора да буде валидан ЈСОН низ.',
    'max'                  => [
        'numeric' => ':attribute не може да буде већи од :max.',
        'file'    => ':attribute не може бити већи од :max килобајта.',
        'string'  => ':attribute не може бити већи од :max карактера.',
        'array'   => ':attribute не може имати више од :max ставки.',
    ],
    'mimes'                => ':attribute мора да буде датотека-фајл типа: :values.',
    'mimetypes'            => ':attribute мора да буде датотека-фајл типа: :values.',
    'min'                  => [
        'numeric' => ':attribute мора бити најмање :min.',
        'file'    => ':attribute мора бити најмање :min килобајта.',
        'string'  => ':attribute мора бити најмање :min карактера.',
        'array'   => ':attribute мора да има најмање :min ставки.',
    ],
    'not_in'               => 'Изабран :attribute је неважећи.',
    'numeric'              => ':attribute мора бити број.',
    'present'              => ':attribute мора бити присутан.',
    'regex'                => ':attribute формат је неважећи.',
    'required'             => ':attribute поље је обавезно.',
    'required_if'          => ':attribute је обавезно када је :other исти као и :other.',
    'required_unless'      => ':other је обавезно осим у случају када је :other у оквиру :values.',
    'required_with'        => ':attribute поље је обавезно када је :values присутна.',
    'required_with_all'    => ':attribute поље је обавезно када је :values присутна.',
    'required_without'     => ':attribute поље је обавезно када није присутан :values.',
    'required_without_all' => ':attribute поље је обавезно када није присутно ни једно од :values.',
    'same'                 => 'Морају се поклапати :attribute и :other .',
    'size'                 => [
        'numeric' => ':attribute мора бити :size.',
        'file'    => ':attribute мора бити :size килобајта.',
        'string'  => ':attribute мора бити :size карактера.',
        'array'   => ':attribute мора садржати :size ставки.',
    ],
    'string'               => ':attribute мора бити низ.',
    'timezone'             => ':attribute мора бити важећа зона.',
    'unique'               => ':attribute је већ заузет.',
    'uploaded'             => ':attribute неуспело отпремање.',
    'url'                  => ':attribute је неважећи формат.',

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
            'rule-name' => 'прилагођена-порука',
        ],
        'invalid_currency' => ':attribute код је неважећи.',
        'invalid_amount'   => ':attribute износ је неважећи.',
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
