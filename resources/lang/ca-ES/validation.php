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
    'accepted_if' => 'Cal acceptar el camp :attribute quan :other és :value.',
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
        'array' => 'El camp :attribute ha de tenir entre :min i :max ítems.',
        'file' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
        'numeric' => 'El camp :attribute ha d\'estar entre :min i :max.',
        'string' => 'El camp :attribute ha de tenir entre :min i :max caràcters.',
    ],
    'boolean' => 'El camp :attribute ha de ser verdader o fals.',
    'confirmed' => 'La confirmació de :attribute no coincideix.',
    'current_password' => 'La contrasenya no és correcta.',
    'date' => 'El camp :attribute no és una data vàlida.',
    'date_equals' => 'El camp :attribute ha de ser una data igual a :date.',
    'date_format' => 'El camp :attribute no concorda amb el format :format.',
    'declined' => 'El camp :attribute ha de ser rebutjat.',
    'declined_if' => 'Cal rebutjar el camp :attribute quan :other és :value.',
    'different' => 'Els camps :attribute i :other han de ser diferents.',
    'digits' => 'El camp :attribute ha de tenir :digits dígits.',
    'digits_between' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
    'dimensions' => 'Les dimensions de la imatge :attribute no són vàlides.',
    'distinct' => 'El camp :attribute té un valor duplicat.',
    'doesnt_start_with' => 'El camp :attribute no pot començar amb un dels valors següents: :values.',
    'double' => 'El camp :attribute ha de ser un número decimal vàlid.',
    'email' => 'El camp :attribute no és un <strong>correu electrònic</strong> vàlid.',
    'ends_with' => 'El camp :attribute ha d\'acabar amb un dels valors següents: :values.',
    'enum' => 'El camp :attribute seleccionat no és vàlid.',
    'exists' => 'El camp :attribute seleccionat és invàlid.',
    'file' => 'El camp :attribute ha de ser un <strong>arxiu</strong>.',
    'filled' => 'El camp :attribute és <strong>obligatori</strong>.',
    'gt' => [
        'array' => 'El camp :attribute ha de tenir més de :value elements.',
        'file' => 'El camp :attribute ha de ser més gran de :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més gran de :value.',
        'string' => 'El camp :attribute ha de ser més gran de :value caràcters.',
    ],
    'gte' => [
        'array' => 'El camp :attribute ha de tenir :value elements o més.',
        'file' => 'El camp :attribute ha de ser més gran o igual de :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més gran o igual de :value.',
        'string' => 'El camp :attribute ha de ser més gran o igual de :value caràcters.',
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
        'array' => 'El camp :attribute ha de tenir menys de :value elements.',
        'file' => 'El camp :attribute ha de ser més petit de :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més petit de :value.',
        'string' => 'El camp :attribute ha de ser més petit de :value caràcters.',
    ],
    'lte' => [
        'array' => 'El camp :attribute no pot tenir més de :value elements.',
        'file' => 'El camp :attribute ha de ser més petit o igual de :value kilobytes.',
        'numeric' => 'El camp :attribute ha de ser més petit o igual de :value.',
        'string' => 'El camp :attribute ha de ser més petit o igual de :value caràcters.',
    ],
    'mac_address' => 'El camp :attribute ha de ser una adreça MAC vàlida.',
    'max' => [
        'array' => 'El camp :attribute no pot tenir més de :max elements.',
        'file' => 'El camp :attribute no pot ser més gran de :max kilobytes.',
        'numeric' => 'El camp :attribute no pot ser més gran de :max.',
        'string' => 'El camp :attribute no pot tenir més de :max caràcters.',
    ],
    'mimes' => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'mimetypes' => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'min' => [
        'array' => 'El camp :attribute ha de tenir almenys :min elements.',
        'file' => 'La mida de :attribute ha de ser d\'almenys :min kilobytes.',
        'numeric' => 'La mida de :attribute ha de ser d\'almenys :min.',
        'string' => 'El camp :attribute ha de contenir almenys :min caràcters.',
    ],
    'multiple_of' => 'El camp :attribute ha de ser un múltiple de :value.',
    'not_in' => 'El camp :attribute seleccionat és invàlid.',
    'not_regex' => 'El format de :attribute és invàlid.',
    'numeric' => 'El camp :attribute ha de ser numèric.',
    'password' => [
        'letters' => 'El camp :attribute ha de contenir almenys una lletra.',
        'mixed' => 'El camp :attribute ha de contenir almenys una lletra majúscula i una lletra minúscula.',
        'numbers' => 'El camp :attribute ha de contenir almenys un número.',
        'symbols' => 'El camp :attribute ha de contenir almenys un símbol.',
        'uncompromised' => 'El camp :attribute no és segur. Si us plau, tria un altre :attribute.',
    ],
    'present' => 'El camp :attribute ha d\'existir.',
    'prohibited' => 'El camp :attribute no està permès.',
    'prohibited_if' => 'El camp :attribute no està permès quan :other és :value.',
    'prohibited_unless' => 'El camp :attribute no està permès a no ser que :other sigui a :values.',
    'prohibits' => 'El camp :attribute no permet que :other hi sigui present.',
    'regex' => 'El format de :attribute és invàlid.',
    'required' => 'El camp :attribute és <strong>obligatori</strong>.',
    'required_array_keys' => 'El camp :attribute ha de contenir entrades per: :values.',
    'required_if' => 'El camp :attribute és obligatori quan :other és :value.',
    'required_unless' => 'El camp :attribute és obligatori a no ser que :other sigui a :values.',
    'required_with' => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_with_all' => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_without' => 'El camp :attribute és obligatori quan no hi ha :values.',
    'required_without_all' => 'El camp :attribute és obligatori quan no hi ha cap valor dels següents: :values.',
    'same' => ':attribute i :other han de coincidir.',
    'size' => [
        'array' => ':attribute ha de contenir :size ítems.',
        'file' => 'El tamany de :attribute ha de ser :size kilobytes.',
        'numeric' => 'El tamany de :attribute ha de ser :size.',
        'string' => ':attribute ha de contenir :size caràcters.',
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
        'invalid_dimension'     => 'Les mides màximes pel camp :attribute són :width px :height px. ',
        'invalid_colour'        => 'El color de :attribute no és vàlid.',
        'invalid_payment_method'=> 'El mètode de pagament no és vàlid.',
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
