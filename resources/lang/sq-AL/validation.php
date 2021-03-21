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

    'accepted'             => ':attribute duhet të pranohet.',
    'active_url'           => ':attribute nuk është adresë e saktë.',
    'after'                => ':attribute duhet të jetë datë pas :date.',
    'after_or_equal'       => ':attribute duhet të jetë një datë pas ose e barabartë me :date.',
    'alpha'                => ':attribute mund të përmbajë vetëm shkronja.',
    'alpha_dash'           => ':attribute mund të përmbajë vetëm shkronja, numra, dhe viza.',
    'alpha_num'            => ':attribute mund të përmbajë vetëm shkronja dhe numra.',
    'array'                => ':attribute duhet të jetë një bashkësi (array).',
    'before'               => ':attribute duhet të jetë datë para :date.',
    'before_or_equal'      => ':attribute duhet të jetë një datë përpara ose e barabartë me :date.',
    'between'              => [
        'numeric' => ':attribute duhet të jetë midis :min - :max.',
        'file'    => ':attribute duhet të jetë midis :min - :max kilobajtëve.',
        'string'  => ':attribute duhet të jetë midis :min - :max karaktereve.',
        'array'   => ':attribute duhet të jetë midis :min - :max elementëve.',
    ],
    'boolean'              => 'Fusha :attribute duhet të jetë e vërtetë ose e gabuar',
    'confirmed'            => ':attribute konfirmimi nuk përputhet.',
    'date'                 => ':attribute nuk është një datë e saktë.',
    'date_format'          => ':attribute nuk i përshtatet formatit :format.',
    'different'            => ':attribute dhe :other duhet të jenë të <strong>ndryshme</strong>.',
    'digits'               => ':attribute duhet të jetë :digits shifra.',
    'digits_between'       => ':attribute duhet të jetë midis :min dhe :max shifra.',
    'dimensions'           => ':attribute ka dimensione të pavlefshme imazhi.',
    'distinct'             => ':attribute fusha ka një vlerë të dyfishtë.',
    'email'                => ':attribute formati është i pasaktë.',
    'ends_with'            => ':attribute duhet të mbarojë me një nga këta vlerat: :values',
    'exists'               => ':attribute përzgjedhur është i/e pasaktë.',
    'file'                 => ':attribute duhet të jetë <strong>dosje</strong>.',
    'filled'               => 'Fusha :attribute është e kërkuar.',
    'image'                => ':attribute duhet të jetë imazh.',
    'in'                   => ':attribute përzgjedhur është i/e pasaktë.',
    'in_array'             => ':attribute fusha nuk ekziston në :other.',
    'integer'              => ':attribute duhet të jetë numër i plotë.',
    'ip'                   => ':attribute duhet të jetë një IP adresë e saktë.',
    'json'                 => ':attribute duhet të jetë një varg JSON i vlefshëm.',
    'max'                  => [
        'numeric' => ':attribute nuk mund të jetë më tepër se :max.',
        'file'    => ':attribute nuk mund të jetë më tepër se :max kilobajtë.',
        'string'  => ':attribute nuk mund të jetë më tepër se :max karaktere.',
        'array'   => ':attribute nuk mund të ketë më tepër se :max elemente.',
    ],
    'mimes'                => ':attribute duhet të jetë një dokument i tipit: :values.',
    'mimetypes'            => ':attribute duhet të jetë një dokument i tipit: :values.',
    'min'                  => [
        'numeric' => ':attribute nuk mund të jetë më pak se :min.',
        'file'    => ':attribute nuk mund të jetë më pak se :min kilobajtë.',
        'string'  => ':attribute nuk mund të jetë më pak se :min karaktere.',
        'array'   => ':attribute nuk mund të ketë më pak se :min elemente.',
    ],
    'not_in'               => ':attribute përzgjedhur është i/e pasaktë.',
    'numeric'              => ':attribute duhet të jetë një numër.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'Formati i :attribute është i pasaktë.',
    'required'             => 'Fusha :attribute është e kërkuar.',
    'required_if'          => 'Fusha :attribute është e kërkuar kur :other është :value.',
    'required_unless'      => ':attribute fusha është e nevojshme në mos :other është në :values.',
    'required_with'        => 'Fusha :attribute është e kërkuar kur :values ekziston.',
    'required_with_all'    => 'Fusha :attribute është e kërkuar kur :values ekziston.',
    'required_without'     => 'Fusha :attribute është e kërkuar kur :values nuk ekziston.',
    'required_without_all' => 'Fusha :attribute është e kërkuar kur nuk ekziston asnjë nga :values.',
    'same'                 => ':attribute dhe :other duhet të përputhen.',
    'size'                 => [
        'numeric' => ':attribute duhet të jetë :size.',
        'file'    => ':attribute duhet të jetë :size kilobajtë.',
        'string'  => ':attribute duhet të jetë :size karaktere.',
        'array'   => ':attribute duhet të ketë :size elemente.',
    ],
    'string'               => ':attribute duhet të jetë <strong>varg</strong>.',
    'timezone'             => ':attribute duhet të jetë zonë e saktë.',
    'unique'               => ':attribute është marrë tashmë.',
    'uploaded'             => ':attribute <strong>ka dështuar</strong> në ngarkim.',
    'url'                  => 'Formati i :attribute është i pasaktë.',

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
            'rule-name'             => 'Mesazh Privat',
        ],
        'invalid_currency'      => 'Kodi :attribute është i pavlefshëm.',
        'invalid_amount'        => 'Shuma :attribute është e pavlefshme.',
        'invalid_extension'     => 'Zgjatja e skedarit është e pavlefshme.',
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
