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

    'accepted' => 'El camp :attribute ha de ser acceptat.',
    'active_url' => 'El camp :attribute no és una URL vàlid.',
    'after' => 'El camp :attribute ha de ser una data posterior a :date.',
    'after_or_equal' => 'El camp :attribute ha de ser una data posterior o igual a :date.',
    'alpha' => 'El camp :attribute només pot contenir lletres.',
    'alpha_dash' => 'El camp :attribute només pot contenir lletres, números i guions.',
    'alpha_num' => 'El camp :attribute només pot contenir lletres i números.',
    'array' => 'El camp :attribute ha de ser una matriu.',
    'before' => 'El camp :attribute ha de ser una data anterior a :date.',
    'before_or_equal' => 'El camp :attribute ha de ser una data anterior o igual a :date.',
    'between' => [
        'numeric' => 'El camp :attribute ha d\'estar entre :min i :max.',
        'file' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
        'string' => 'El camp :attribute ha de tenir entre :min i :max caràcters.',
        'array' => 'El camp :attribute ha de tenir entre :min i :max ítems.',
    ],
    'boolean' => 'El camp :attribute ha de ser verdader o fals.',
    'confirmed' => 'La confirmació de :attribute no coincideix.',
    'current_password' => 'La contrasenya no és correcta.',
    'date' => 'El camp :attribute no és una data vàlida.',
    'date_equals' => 'El camp :attribute ha de ser una data igual a :date.',
    'date_format' => 'El camp :attribute no concorda amb el format :format.',
    'different' => 'Els camps :attribute i :other han de ser diferents.',
    'digits' => 'El camp :attribute ha de tenir :digits dígits.',
    'digits_between' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
    'dimensions' => 'Les dimensions de la imatge :attribute no són vàlides.',
    'distinct' => 'El camp :attribute té un valor duplicat.',
    'email' => 'El camp :attribute no és un <strong>correu electrònic</strong> vàlid.',
    'ends_with' => 'El camp :attribute ha d\'acabar amb un dels valors següents: :values.',
    'exists' => 'El camp :attribute seleccionat és invàlid.',
    'file' => 'El camp :attribute ha de ser un <strong>arxiu</strong>.',
    'filled' => 'El camp :attribute és <strong>obligatori</strong>.',
    'gt' => [
        'numeric' => 'El camp :attribute ha de ser més gran de :value.',
        'file' => 'El camp :attribute ha de ser més gran de :value kilobytes.',
        'string' => 'El camp :attribute ha de ser més gran de :value caràcters.',
        'array' => 'El camp :attribute ha de tenir més de :value elements.',
    ],
    'gte' => [
        'numeric' => 'El camp :attribute ha de ser més gran o igual de :value.',
        'file' => 'El camp :attribute ha de ser més gran o igual de :value kilobytes.',
        'string' => 'El camp :attribute ha de ser més gran o igual de :value caràcters.',
        'array' => 'El camp :attribute ha de tenir :value elements o més.',
    ],
    'image' => 'El camp :attribute ha de ser una <strong>imatge</strong>.',
    'in' => 'El camp :attribute seleccionat és invàlid',
    'in_array' => 'El camp :attribute no existeix dins de :other.',
    'integer' => 'El camp :attribute ha de ser un nombre <strong>enter</strong>.',
    'ip' => 'El camp :attribute ha de ser una adreça IP vàlida.',
    'ipv4' => 'El camp :attribute ha de ser una adreça IPv4 vàlida.',
    'ipv6' => 'El camp :attribute ha de ser una adreça IPv6 vàlida.',
    'json' => 'El camp :attribute ha de ser una cadena JSON vàlida.',
    'lt' => [
        'numeric' => 'El camp :attribute ha de ser més petit de :value.',
        'file' => 'El camp :attribute ha de ser més petit de :value kilobytes.',
        'string' => 'El camp :attribute ha de ser més petit de :value caràcters.',
        'array' => 'El camp :attribute ha de tenir menys de :value elements.',
    ],
    'lte' => [
        'numeric' => 'El camp :attribute ha de ser més petit o igual de :value.',
        'file' => 'El camp :attribute ha de ser més petit o igual de :value kilobytes.',
        'string' => 'El camp :attribute ha de ser més petit o igual de :value caràcters.',
        'array' => 'El camp :attribute no pot tenir més de :value elements.',
    ],
    'max' => [
        'numeric' => 'El camp :attribute no pot ser més gran de :max.',
        'file' => 'El camp :attribute no pot ser més gran de :max kilobytes.',
        'string' => 'El camp :attribute no pot tenir més de :max caràcters.',
        'array' => 'El camp :attribute no pot tenir més de :max elements.',
    ],
    'mimes' => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'mimetypes' => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'min' => [
        'numeric' => 'La mida de :attribute ha de ser d\'almenys :min.',
        'file' => 'La mida de :attribute ha de ser d\'almenys :min kilobytes.',
        'string' => 'El camp :attribute ha de contenir almenys :min caràcters.',
        'array' => 'El camp :attribute ha de tenir almenys :min elements.',
    ],
    'multiple_of' => 'El camp :attribute ha de ser un múltiple de :value.',
    'not_in' => 'El camp :attribute seleccionat és invàlid.',
    'not_regex' => 'El format de :attribute és invàlid.',
    'numeric' => 'El camp :attribute ha de ser numèric.',
    'password' => 'La contrasenya no és correcta.',
    'present' => 'El camp :attribute ha d\'existir.',
    'regex' => 'El format de :attribute és invàlid.',
    'required' => 'El camp :attribute és <strong>obligatori</strong>.',
    'required_if' => 'El camp :attribute és obligatori quan :other és :value.',
    'required_unless' => 'El camp :attribute és obligatori a no ser que :other sigui a :values.',
    'required_with' => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_with_all' => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_without' => 'El camp :attribute és obligatori quan no hi ha :values.',
    'required_without_all' => 'El camp :attribute és obligatori quan no hi ha cap valor dels següents: :values.',
    'prohibited' => 'El camp :attribute no està permès.',
    'prohibited_if' => 'El camp :attribute no està permès quan :other és :value.',
    'prohibited_unless' => 'El camp :attribute no està permès a no ser que :other sigui a :values.',
    'same' => ':attribute i :other han de coincidir.',
    'size' => [
        'numeric' => 'El tamany de :attribute ha de ser :size.',
        'file' => 'El tamany de :attribute ha de ser :size kilobytes.',
        'string' => ':attribute ha de contenir :size caràcters.',
        'array' => ':attribute ha de contenir :size ítems.',
    ],
    'starts_with' => 'El camp :attribute ha de començar amb un dels valors següents: :values.',
    'string' => 'El camp :attribute ha de ser una cadena.',
    'timezone' => 'El camp :attribute ha de ser una zona vàlida.',
    'unique' => ':attribute ja està registrat i no es pot repetir.',
    'uploaded' => ':attribute ha fallat al pujar.',
    'url' => ':attribute no és una adreça web vàlida.',
    'uuid' => 'El camp :attribute ha de ser un UUID vàlid.',

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
        'invalid_currency'      => 'El camp :attribute és invàlid.',
        'invalid_amount'        => 'La quantitat :attribute no és vàlida.',
        'invalid_extension'     => 'L\'extensió del fitxer no és vàlida.',
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
