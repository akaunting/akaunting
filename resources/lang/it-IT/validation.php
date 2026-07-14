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

    'accepted' => ':attribute deve essere accettato.',
    'accepted_if' => ':attribute deve essere accettato quando :other è :value.',
    'active_url' => ':attribute non è un URL valido.',
    'after' => ':attribute deve essere una data successiva al :date.',
    'after_or_equal' => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha' => ':attribute può contenere solo lettere.',
    'alpha_dash' => ':attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num' => ':attribute può contenere solo lettere e numeri.',
    'array' => ':attribute deve essere un array.',
    'before' => ':attribute deve essere una data precedente al :date.',
    'before_or_equal' => ':attribute deve essere una data precedente o uguale al :date.',
    'between' => [
        'array' => ':attribute deve avere tra :min - :max elementi.',
        'file' => ':attribute deve trovarsi tra :min - :max kilobytes.',
        'numeric' => ':attribute deve trovarsi tra :min - :max.',
        'string' => ':attribute deve trovarsi tra :min - :max caratteri.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'Il campo di conferma per :attribute non coincide.',
    'current_password' => 'La password non è corretta.',
    'date' => ':attribute non è una data valida.',
    'date_equals' => ':attribute deve essere una data uguale a :date.',
    'date_format' => ':attribute non coincide con il formato :format.',
    'declined' => 'Il :attribute deve essere rifiutato.',
    'declined_if' => ':attribute deve essere rifiutato quando :other è :value.',
    'different' => ':attribute e :other devono essere diversi.',
    'digits' => ':attribute deve essere di :digits cifre.',
    'digits_between' => ':attribute deve essere tra :min e :max cifre.',
    'dimensions' => 'Le dimensioni dell\'immagine di :attribute non sono valide.',
    'distinct' => ':attribute contiene un valore duplicato.',
    'doesnt_start_with' => ':attribute potrebbe non iniziare con uno dei seguenti: :values.',
    'double' => 'Il :attribute deve essere un doppio valido.',
    'email' => ':attribute deve essere un indirizzo email valido.',
    'ends_with' => ':attribute deve terminare con uno dei seguenti: :values',
    'enum' => 'Il :attribute selezionato non è valido.',
    'exists' => ':attribute selezionato non è valido.',
    'file' => ':attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve avere un valore.',
    'gt' => [
        'array' => ':attribute deve contenere più di articoli :value.',
        'file' => ':attribute deve essere maggiore di :value kilobyte.',
        'numeric' => ':attribute deve essere maggiore di :value.',
        'string' => ':attribute deve essere maggiore di caratteri :value.',
    ],
    'gte' => [
        'array' => ':attribute deve avere articoli :value o più.',
        'file' => ':attribute deve essere maggiore o uguale ai kilobyte :value.',
        'numeric' => ':attribute deve essere maggiore o uguale a :value.',
        'string' => ':attribute deve essere maggiore o uguale ai caratteri :value.',
    ],
    'image' => ':attribute deve essere un\'immagine.',
    'in' => ':attribute selezionato non è valido.',
    'in_array' => 'Il valore del campo :attribute non esiste in :other.',
    'in_detailed' => 'Il valore :attribute ":value" non è valido. Previsto uno tra: :values',
    'integer' => ':attribute deve essere un numero intero.',
    'ip' => ':attribute deve essere un indirizzo IP valido.',
    'ipv4' => ':attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => ':attribute deve essere un indirizzo IPv6 valido.',
    'json' => ':attribute deve essere una stringa JSON valida.',
    'lt' => [
        'array' => 'Il modello :attribute deve contenere meno di articoli :value.',
        'file' => ':attribute deve essere inferiore a :value kilobyte.',
        'numeric' => ':attribute deve essere inferiore a :value.',
        'string' => ':attribute deve contenere meno di :value caratteri.',
    ],
    'lte' => [
        'array' => 'Il :attribute non deve contenere più di articoli :value.',
        'file' => ':attribute deve essere inferiore o uguale ai kilobyte :value.',
        'numeric' => ':attribute deve essere inferiore o uguale a :value.',
        'string' => ':attribute deve essere inferiore o uguale ai caratteri :value.',
    ],
    'mac_address' => ':attribute deve essere un indirizzo MAC valido.',
    'max' => [
        'array' => ':attribute non può avere più di :max elementi.',
        'file' => ':attribute non può essere superiore a :max kilobytes.',
        'numeric' => ':attribute non può essere superiore a :max.',
        'string' => ':attribute non può contenere più di :max caratteri.',
    ],
    'mimes' => ':attribute deve essere del tipo: :values.',
    'mimetypes' => ':attribute deve essere del tipo: :values.',
    'min' => [
        'array' => ':attribute deve avere almeno :min elementi.',
        'file' => ':attribute deve essere almeno di :min kilobytes.',
        'numeric' => ':attribute deve essere almeno :min.',
        'string' => ':attribute deve contenere almeno :min caratteri.',
    ],
    'multiple_of' => ':attribute deve essere un multiplo di :value.',
    'not_in' => 'Il valore selezionato per :attribute non è valido.',
    'not_regex' => 'Il formato :attribute non è valido.',
    'numeric' => ':attribute deve essere un numero.',
    'password' => [
        'letters' => ':attribute deve contenere almeno una lettera.',
        'mixed' => ':attribute deve contenere almeno una lettera maiuscola e una minuscola.',
        'numbers' => 'Il :attribute deve contenere almeno un numero.',
        'symbols' => ':attribute deve contenere almeno un simbolo.',
        'uncompromised' => 'Il dato :attribute è apparso in una fuga di dati. Scegli un :attribute diverso.',
    ],
    'present' => 'Il campo :attribute deve essere presente.',
    'prohibited' => 'Il campo :attribute è vietato.',
    'prohibited_if' => 'Il campo :attribute è vietato quando :other è :value.',
    'prohibited_unless' => 'Il campo :attribute è vietato a meno che :other non sia in :values.',
    'prohibits' => 'Il campo :attribute impedisce la presenza di :other.',
    'regex' => 'Il formato :attribute non è valido.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_array_keys' => 'Il campo :attribute deve contenere voci per: :values.',
    'required_if' => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless' => 'Il campo :attribute è richiesto a meno che :other sia in :values.',
    'required_with' => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_without' => 'Il campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessuno di :values è presente.',
    'same' => ':attribute e :other devono coincidere.',
    'size' => [
        'array' => ':attribute deve contenere :size elementi.',
        'file' => ':attribute deve essere :size kilobytes.',
        'numeric' => ':attribute deve essere :size.',
        'string' => 'I caratteri :attribute devono essere caratteri :size.',
    ],
    'starts_with' => ':attribute deve iniziare con uno dei seguenti: :values.',
    'string' => ':attribute deve essere una stringa.',
    'timezone' => ':attribute deve essere una zona valida.',
    'unique' => 'Il :attribute è già stato preso.',
    'uploaded' => 'Impossibile caricare :attribute.',
    'url' => ':attribute deve essere un URL valido.',
    'uuid' => ':attribute deve essere un UUID valido.',

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
            'rule-name' => 'messaggio-personalizzato',
        ],
        'invalid_currency'      => ':attribute codice non è valido.',
        'invalid_amount'        => 'L\'importo :attribute non è valido.',
        'invalid_quantity'      => ':attribute non è un\'espressione matematica valida.',
        'invalid_extension'     => 'L\'estensione del file non è valida.',
        'invalid_dimension'     => 'Le dimensioni :attribute devono essere max :width x :height px.',
        'invalid_colour'        => 'Il colore :attribute non è valido.',
        'invalid_payment_method'=> 'Il metodo di pagamento non è valido.',
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
