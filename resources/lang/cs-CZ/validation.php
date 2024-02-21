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

    'accepted' => ':attribute musí být přijat.',
    'accepted_if' => ':attribute musí být přijat, pokud :other je :value.',
    'active_url' => ':attribute není platná URL adresa.',
    'after' => ':attribute musí být datum po :date.',
    'after_or_equal' => ':attribute musí být datum po nebo rovné :date.',
    'alpha' => ':attribute může obsahovat pouze písmena.',
    'alpha_dash' => ':attribute může obsahovat pouze písmena, číslice, pomlčky a podtržítka. České znaky (á, é, í, ó, ú, ů, ž, š, č, ř, ď, ť, ň) nejsou podporovány.',
    'alpha_num' => ':attribute může obsahovat pouze písmena a číslice.',
    'array' => ':attribute musí být pole.',
    'before' => ':attribute musí být datum před :date.',
    'before_or_equal' => ':attribute musí být datum před nebo rovné :date.',
    'between' => [
        'array' => ':attribute musí obsahovat nejméně :min a nesmí obsahovat více než :max prvků.',
        'file' => ':attribute musí být větší než :min a menší než :max kilobytů.',
        'numeric' => ':attribute musí být hodnota mezi :min a :max.',
        'string' => ':attribute musí být delší než :min a kratší než :max znaků.',
    ],
    'boolean' => ':attribute musí být true nebo false.',
    'confirmed' => 'Potvrzení :attribute nesouhlasí.',
    'current_password' => 'Zadané heslo není správné.',
    'date' => ':attribute musí být platné datum.',
    'date_equals' => ':attribute musí být datum shodné s :date.',
    'date_format' => ':attribute není platný formát data podle :format.',
    'declined' => ':attribute musí být zamítnut.',
    'declined_if' => ':attribute musí být odmítnut, pokud :other je :value.',
    'different' => ':attribute a :other se musí <strong>lišit</strong>.',
    'digits' => ':attribute musí obsahovat :digits číslic.',
    'digits_between' => ':attribute musí být dlouhé nejméně :min a nejvíce :max pozic.',
    'dimensions' => 'Obrázek :attribute má neplatné rozměry.',
    'distinct' => ':attribute má duplicitní hodnotu.',
    'doesnt_start_with' => ':attribute nesmí začínat jedním z následujících :values.',
    'email' => ':attribute musí být platná <strong>e-mailová adresa</strong>.',
    'ends_with' => ':attribute musí končit jednou z následujících hodnot: :values',
    'enum' => 'Vybraný :attribute je neplatný.',
    'exists' => 'Zvolená hodnota pro :attribute není platná.',
    'file' => ':attribute musí být soubor <strong></strong>.',
    'filled' => ':attribute musí mít <strong>hodnotu</strong>.',
    'gt' => [
        'array' => 'Pole :attribute musí mít více prvků než :value.',
        'file' => 'Velikost souboru :attribute musí být větší než :value kB.',
        'numeric' => ':attribute musí být větší než :value.',
        'string' => 'Počet znaků :attribute musí být větší než :value.',
    ],
    'gte' => [
        'array' => 'Pole :attribute musí mít :value prvků nebo více.',
        'file' => 'Velikost souboru :attribute musí být větší nebo rovno :value kB.',
        'numeric' => ':attribute musí být větší nebo rovno :value.',
        'string' => 'Počet znaků :attribute musí být větší nebo rovno :value.',
    ],
    'image' => ':attribute musí být obrázek <strong></strong>.',
    'in' => 'Zvolená hodnota pro :attribute je neplatná.',
    'in_array' => ':attribute není obsažen v :other.',
    'integer' => ':attribute musí být <strong>celé číslo</strong>.',
    'ip' => ':attribute není platná IP adresa.',
    'ipv4' => ':attribute musí být platná IPv4 adresa.',
    'ipv6' => ':attribute musí být platná IPv6 adresa.',
    'json' => ':attribute není platný řetězec JSON.',
    'lt' => [
        'array' => 'Pole :attribute musí mít méně prvků než :value.',
        'file' => 'Velikost souboru :attribute musí být menší než :value kB.',
        'numeric' => ':attribute musí být menší než :value.',
        'string' => 'Počet znaků :attribute musí být menší než :value.',
    ],
    'lte' => [
        'array' => ':attribute nesmí obsahovat více než :value položek.',
        'file' => ':attribute musí být menší nebo rovno než :value kB',
        'numeric' => ':attribute musí být menší nebo rovno než :value.',
        'string' => 'Počet znaků :attribute musí být menší nebo rovno než :value.',
    ],
    'mac_address' => ':attribute musí být platná MAC adresa.',
    'max' => [
        'array' => ':attribute nesmí obsahovat více než :max prvků.',
        'file' => ':attribute musí být menší než :max kilobytů.',
        'numeric' => ':attribute musí být nižší než :max.',
        'string' => ':attribute musí být kratší než :max znaků.',
    ],
    'mimes' => ':attribute není soubor jednoho z následujících typů :values.',
    'mimetypes' => ':attribute není soubor jednoho z následujících typů :values.',
    'min' => [
        'array' => ':attribute musí obsahovat více než :min prvků.',
        'file' => ':attribute musí být větší než :min kilobytů.',
        'numeric' => ':attribute musí být větší než :min.',
        'string' => ':attribute musí být delší než :min znaků.',
    ],
    'multiple_of' => ':attribute musí být násobek :value.',
    'not_in' => 'Zvolená hodnota pro :attribute je neplatná.',
    'not_regex' => 'Formát :attribute je neplatný.',
    'numeric' => ':attribute musí být číslo.',
    'password' => [
        'letters' => ':attribute musí obsahovat alespoň jedno písmeno.',
        'mixed' => ':attribute musí obsahovat alespoň jedno velké písmeno a jedno malé písmeno.',
        'numbers' => ':attribute musí obsahovat alespoň jedno číslo.',
        'symbols' => ':attribute musí obsahovat alespoň jeden symbol.',
        'uncompromised' => 'Uvedený :attribute se objevil v úniku dat. Zvolte prosím jiný :attribute.',
    ],
    'present' => ':attribute políčko musí být <strong>vyplněno</strong>.',
    'prohibited' => 'Pole :attribute je zakázáno.',
    'prohibited_if' => ':attribute je zakázáno pokud :other je :value.',
    'prohibited_unless' => ':attribute je zakázáno pokud :other není v :values.',
    'prohibits' => 'Pole :attribute zakazuje :other být přítomno.',
    'regex' => 'Formát :attribute je <strong>neplatný</strong>.',
    'required' => ':attribute musí být <strong>vyplněno</strong>.',
    'required_array_keys' => ':attribute musí obsahovat záznamy pro: :values.',
    'required_if' => ':attribute musí být vyplněno pokud :other je :value.',
    'required_unless' => ':attribute musí být vyplněno pokud :other není :values.',
    'required_with' => ':attribute musí být vyplněno pokud :values je vyplněno.',
    'required_with_all' => ':attribute musí být vyplněno pokud je vyplněno :values.',
    'required_without' => ':attribute musí být vyplněno pokud :values není vyplněno.',
    'required_without_all' => ':attribute musí být vyplněno pokud není žádné z :values zvoleno.',
    'same' => ':attribute a :other se musí shodovat.',
    'size' => [
        'array' => ':attribute musí obsahovat právě :size prvků.',
        'file' => ':attribute musí mít přesně :size kilobytů.',
        'numeric' => ':attribute musí být přesně :size.',
        'string' => ':attribute musí být dlouhý <strong>:size znaků</strong>.',
    ],
    'starts_with' => ':attribute musí začínat jednou z těchto hodnot: :values.',
    'string' => ':attribute musí být <strong>řetězec</strong>.',
    'timezone' => ':attribute musí být platná časová zóna.',
    'unique' => ':attribute již byl <strong>použit</strong>.',
    'uploaded' => ':attribute se <strong>nepodařilo</strong> nahrát.',
    'url' => 'Formát :attribute je <strong>neplatný</strong>.',
    'uuid' => ':attribute musí být platný UUID.',

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
        'invalid_currency'      => 'Kód :attribute není platný.',
        'invalid_amount'        => 'Zvolená hodnota pro :attribute je neplatná.',
        'invalid_extension'     => 'Přípona souboru je neplatná.',
        'invalid_dimension'     => ':attribute musí mít maximálně :width x :height px.',
        'invalid_colour'        => ':attribute barva je neplatná.',
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
