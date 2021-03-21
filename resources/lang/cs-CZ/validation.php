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

    'accepted'             => ':attribute musí být přijat.',
    'active_url'           => ':attribute není platná URL adresa.',
    'after'                => ':attribute musí být datum po :date.',
    'after_or_equal'       => ':attribute musí být datum po nebo rovné :date.',
    'alpha'                => ':attribute může obsahovat pouze písmena.',
    'alpha_dash'           => ':attribute může obsahovat pouze písmena, číslice, pomlčky a podtržítka. České znaky (á, é, í, ó, ú, ů, ž, š, č, ř, ď, ť, ň) nejsou podporovány.',
    'alpha_num'            => ':attribute může obsahovat pouze písmena a číslice.',
    'array'                => ':attribute musí být pole.',
    'before'               => ':attribute musí být datum před :date.',
    'before_or_equal'      => ':attribute musí být datum před nebo rovné :date.',
    'between'              => [
        'numeric' => ':attribute musí být hodnota mezi :min a :max.',
        'file'    => ':attribute musí být větší než :min a menší než :max kilobytů.',
        'string'  => ':attribute musí být delší než :min a kratší než :max znaků.',
        'array'   => ':attribute musí obsahovat nejméně :min a nesmí obsahovat více než :max prvků.',
    ],
    'boolean'              => ':attribute musí být true nebo false.',
    'confirmed'            => 'Potvrzení :attribute nesouhlasí.',
    'date'                 => ':attribute musí být platné datum.',
    'date_format'          => ':attribute není platný formát data podle :format.',
    'different'            => ':attribute a :other se musí <strong>lišit</strong>.',
    'digits'               => ':attribute musí obsahovat :digits číslic.',
    'digits_between'       => ':attribute musí být dlouhé nejméně :min a nejvíce :max pozic.',
    'dimensions'           => 'Obrázek :attribute má neplatné rozměry.',
    'distinct'             => ':attribute má duplicitní hodnotu.',
    'email'                => ':attribute musí být platná <strong>e-mailová adresa</strong>.',
    'ends_with'            => ':attribute musí končit jednou z následujících hodnot: :values',
    'exists'               => 'Zvolená hodnota pro :attribute není platná.',
    'file'                 => ':attribute musí být soubor <strong></strong>.',
    'filled'               => ':attribute musí mít <strong>hodnotu</strong>.',
    'image'                => ':attribute musí být obrázek <strong></strong>.',
    'in'                   => 'Zvolená hodnota pro :attribute je neplatná.',
    'in_array'             => ':attribute není obsažen v :other.',
    'integer'              => ':attribute musí být <strong>celé číslo</strong>.',
    'ip'                   => ':attribute není platná IP adresa.',
    'json'                 => ':attribute není platný řetězec JSON.',
    'max'                  => [
        'numeric' => ':attribute musí být nižší než :max.',
        'file'    => ':attribute musí být menší než :max kilobytů.',
        'string'  => ':attribute musí být kratší než :max znaků.',
        'array'   => ':attribute nesmí obsahovat více než :max prvků.',
    ],
    'mimes'                => ':attribute není soubor jednoho z následujících typů :values.',
    'mimetypes'            => ':attribute není soubor jednoho z následujících typů :values.',
    'min'                  => [
        'numeric' => ':attribute musí být větší než :min.',
        'file'    => ':attribute musí být větší než :min kilobytů.',
        'string'  => ':attribute musí být delší než :min znaků.',
        'array'   => ':attribute musí obsahovat více než :min prvků.',
    ],
    'not_in'               => 'Zvolená hodnota pro :attribute je neplatná.',
    'numeric'              => ':attribute musí být číslo.',
    'present'              => ':attribute políčko musí být <strong>vyplněno</strong>.',
    'regex'                => 'Formát :attribute je <strong>neplatný</strong>.',
    'required'             => ':attribute musí být <strong>vyplněno</strong>.',
    'required_if'          => ':attribute musí být vyplněno pokud :other je :value.',
    'required_unless'      => ':attribute musí být vyplněno pokud :other není :values.',
    'required_with'        => ':attribute musí být vyplněno pokud :values je vyplněno.',
    'required_with_all'    => ':attribute musí být vyplněno pokud je vyplněno :values.',
    'required_without'     => ':attribute musí být vyplněno pokud :values není vyplněno.',
    'required_without_all' => ':attribute musí být vyplněno pokud není žádné z :values zvoleno.',
    'same'                 => ':attribute a :other se musí shodovat.',
    'size'                 => [
        'numeric' => ':attribute musí být přesně :size.',
        'file'    => ':attribute musí mít přesně :size kilobytů.',
        'string'  => ':attribute musí být dlouhý <strong>:size znaků</strong>.',
        'array'   => ':attribute musí obsahovat právě :size prvků.',
    ],
    'string'               => ':attribute musí být <strong>řetězec</strong>.',
    'timezone'             => ':attribute musí být platná časová zóna.',
    'unique'               => ':attribute již byl <strong>použit</strong>.',
    'uploaded'             => ':attribute se <strong>nepodařilo</strong> nahrát.',
    'url'                  => 'Formát :attribute je <strong>neplatný</strong>.',

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
        'invalid_currency'      => 'Kód :attribute není platný.',
        'invalid_amount'        => 'Zvolená hodnota pro :attribute je neplatná.',
        'invalid_extension'     => 'Přípona souboru je neplatná.',
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
