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

    'accepted'             => ':attribute deve essere accettato.',
    'active_url'           => ':attribute non è un URL valido.',
    'after'                => ':attribute deve essere una data successiva al :date.',
    'after_or_equal'       => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha'                => ':attribute può contenere solo lettere.',
    'alpha_dash'           => ':attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num'            => ':attribute può contenere solo lettere e numeri.',
    'array'                => ':attribute deve essere un array.',
    'before'               => ':attribute deve essere una data precedente al :date.',
    'before_or_equal'      => ':attribute deve essere una data precedente o uguale al :date.',
    'between'              => [
        'numeric' => ':attribute deve trovarsi tra :min - :max.',
        'file'    => ':attribute deve trovarsi tra :min - :max kilobytes.',
        'string'  => ':attribute deve trovarsi tra :min - :max caratteri.',
        'array'   => ':attribute deve avere tra :min - :max elementi.',
    ],
    'boolean'              => 'Il campo :attribute deve essere vero o falso.',
    'confirmed'            => 'Il campo di conferma per :attribute non coincide.',
    'date'                 => ':attribute non è una data valida.',
    'date_format'          => ':attribute non coincide con il formato :format.',
    'different'            => ':attribute e :other devono essere <strong>differenti</strong>.',
    'digits'               => ':attribute deve essere di :digits cifre.',
    'digits_between'       => ':attribute deve essere tra :min e :max cifre.',
    'dimensions'           => 'Le dimensioni dell\'immagine di :attribute non sono valide.',
    'distinct'             => ':attribute contiene un valore duplicato.',
    'email'                => ':attribute non è <strong>valido</strong>.',
    'ends_with'            => ':attribute deve terminare con uno dei seguenti: :values',
    'exists'               => ':attribute selezionato non è valido.',
    'file'                 => ':attribute deve essere un file <strong></strong>.',
    'filled'               => 'Il campo :attribute deve avere un valore <strong></strong>.',
    'image'                => 'L\' :attributo deve essere un<strong>image</strong>.',
    'in'                   => ':attribute selezionato non è valido.',
    'in_array'             => 'Il valore del campo :attribute non esiste in :other.',
    'integer'              => 'L\'\' :attributo deve essere un <strong>numero intero</strong>.',
    'ip'                   => ':attribute deve essere un indirizzo IP valido.',
    'json'                 => ':attribute deve essere una stringa JSON valida.',
    'max'                  => [
        'numeric' => ':attribute non può essere superiore a :max.',
        'file'    => ':attribute non può essere superiore a :max kilobytes.',
        'string'  => ':attribute non può contenere più di :max caratteri.',
        'array'   => ':attribute non può avere più di :max elementi.',
    ],
    'mimes'                => ':attribute deve essere del tipo: :values.',
    'mimetypes'            => ':attribute deve essere del tipo: :values.',
    'min'                  => [
        'numeric' => ':attribute deve essere almeno :min.',
        'file'    => ':attribute deve essere almeno di :min kilobytes.',
        'string'  => ':attribute deve contenere almeno :min caratteri.',
        'array'   => ':attribute deve avere almeno :min elementi.',
    ],
    'not_in'               => 'Il valore selezionato per :attribute non è valido.',
    'numeric'              => ':attribute deve essere un numero.',
    'present'              => 'Il campo :attributo deve essere <strong>presente</strong>.',
    'regex'                => 'Il formato :attribute non è <strong>valido</strong>.',
    'required'             => 'Il campo :attributo è <strong>obbligatorio</strong>.',
    'required_if'          => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è richiesto a meno che :other sia in :values.',
    'required_with'        => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_with_all'    => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_without'     => 'Il campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessuno di :values è presente.',
    'same'                 => ':attribute e :other devono coincidere.',
    'size'                 => [
        'numeric' => ':attribute deve essere :size.',
        'file'    => ':attribute deve essere :size kilobytes.',
        'string'  => 'L\' :attributo deve essere di <strong>:dimensione caratteri</strong>.',
        'array'   => ':attribute deve contenere :size elementi.',
    ],
    'string'               => 'L\' :attributo deve essere una <strong>stringa</strong>.',
    'timezone'             => ':attribute deve essere una zona valida.',
    'unique'               => 'L\' :attributo è già stato <strong>preso</strong>.',
    'uploaded'             => 'L\' : attributo <strong>caricamento</strong> non riuscito.',
    'url'                  => 'Il formato :attribute non è <strong>valido</strong>.',

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
            'rule-name'             => 'messaggio-personalizzato',
        ],
        'invalid_currency'      => ':attribute codice non è valido.',
        'invalid_amount'        => 'La quantità :l\'attributo non è valido.',
        'invalid_extension'     => 'L\'estensione del file non è valida.',
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
