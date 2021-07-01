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

    'accepted' => ':attribute måste accepteras.',
    'active_url' => ':attribute är inte en giltig webbadress.',
    'after' => ':attribute måste vara ett datum efter den :date.',
    'after_or_equal' => ':attribute måste vara ett datum senare eller samma dag som :date.',
    'alpha' => ':attribute får endast innehålla bokstäver.',
    'alpha_dash' => ':attribute får endast innehålla bokstäver, siffror och bindestreck.',
    'alpha_num' => ':attribute får endast innehålla bokstäver och siffror.',
    'array' => ':attribute måste vara en array.',
    'before' => ':attribute måste vara ett datum innan den :date.',
    'before_or_equal' => ':attribute måste vara ett datum före eller samma dag som :date.',
    'between' => [
        'numeric' => ':attribute måste vara en siffra mellan :min och :max.',
        'file' => ':attribute måste vara mellan :min till :max kilobyte stor.',
        'string' => ':attribute måste innehålla :min till :max tecken.',
        'array' => ':attribute måste innehålla mellan :min - :max objekt.',
    ],
    'boolean' => ':attribute måste vara sant eller falskt.',
    'confirmed' => ':attribute bekräftelsen matchar inte.',
    'current_password' => 'Felaktigt lösenord.',
    'date' => ':attribute är inte ett giltigt datum.',
    'date_equals' => ':attribute måste vara ett datum som motsvarar :date.',
    'date_format' => ':attribute matchar inte formatet :format.',
    'different' => ':attribute och :other måste vara <strong>olika</strong>.',
    'digits' => ':attribute måste vara :digits tecken.',
    'digits_between' => ':attribute måste vara mellan :min och :max tecken.',
    'dimensions' => ':attribute har felaktiga bilddimensioner.',
    'distinct' => ':attribute innehåller fler än en repetition av samma element.',
    'email' => ':attribute måste vara korrekt <strong>epost adress</strong>.',
    'ends_with' => ':attribute måste avslutas med en av följande: :values',
    'exists' => ':attribute är ogiltigt.',
    'file' => ':attribute måste vara en <strong>fil</strong>.',
    'filled' => ':attribute fältet måste ha ett <strong>värde</strong>.',
    'gt' => [
        'numeric' => ':attribute måste vara större än :value.',
        'file' => ':attribute måste vara större än :value kilobyte.',
        'string' => ':attribute måste vara större än :value tecken.',
        'array' => ':attribute måste ha mer än :value objekt.',
    ],
    'gte' => [
        'numeric' => ':attribute måste vara större än eller lika med :value.',
        'file' => ':attribute måste vara större än eller lika med :value kilobyte.',
        'string' => ':attribute måste vara större än eller lika med :value tecken.',
        'array' => ':attribute måste ha :value objekt eller mer.',
    ],
    'image' => ':attribute måste vara en <strong>bild</strong>.',
    'in' => ':attribute är ogiltigt.',
    'in_array' => ':attribute finns inte i :other.',
    'integer' => ':attribute måste vara ett <strong>heltal</strong>.',
    'ip' => ':attribute måste vara en giltig IP-adress.',
    'ipv4' => ':attribute måste vara en giltig IPv4-adress.',
    'ipv6' => ':attribute måste vara en giltig IPv6-adress.',
    'json' => ':attribute måste vara en giltig JSON-sträng.',
    'lt' => [
        'numeric' => ':attribute måste vara mindre än :value.',
        'file' => ':attribute måste vara mindre än :value kilobyte.',
        'string' => ':attribute måste vara mindre än :value tecken.',
        'array' => ':attribute måste ha mindre än :value objekt.',
    ],
    'lte' => [
        'numeric' => ':attribute måste vara mindre än eller lika med :value.',
        'file' => ':attribute måste vara mindre än eller lika med :value kilobyte.',
        'string' => ':attribute måste vara mindre än eller lika med :value tecken.',
        'array' => ':attribute får inte ha mer än :value objekt.',
    ],
    'max' => [
        'numeric' => ':attribute får inte vara större än :max.',
        'file' => ':attribute får max vara :max kilobyte stor.',
        'string' => ':attribute får max innehålla :max tecken.',
        'array' => ':attribute får inte innehålla mer än :max objekt.',
    ],
    'mimes' => ':attribute måste vara en fil av typen: :values.',
    'mimetypes' => ':attribute måste vara en fil av typen: :values.',
    'min' => [
        'numeric' => ':attribute måste vara större än :min.',
        'file' => ':attribute måste vara minst :min kilobyte stor.',
        'string' => ':attribute måste innehålla minst :min tecken.',
        'array' => ':attribute måste innehålla minst :min objekt.',
    ],
    'multiple_of' => ':attribute måste vara en multipel av :value.',
    'not_in' => ':attribute är ogiltigt.',
    'not_regex' => ':attribute format är ogiltigt.',
    'numeric' => ':attribute måste vara en siffra.',
    'password' => 'Lösenordet är felaktigt.',
    'present' => ':attribute fältet måste vara <strong>närvarande</strong>.',
    'regex' => ':attribute formatet är <strong>ogiltigt</strong>.',
    'required' => ':attribute fältet är <strong>nödvändigt</strong>.',
    'required_if' => ':attribute är obligatoriskt när :other är :value.',
    'required_unless' => ':attribute är obligatoriskt när inte :other finns bland :values.',
    'required_with' => ':attribute är obligatoriskt när :values är ifyllt.',
    'required_with_all' => ':attribute är obligatoriskt när :values är ifyllt.',
    'required_without' => ':attribute är obligatoriskt när :values ej är ifyllt.',
    'required_without_all' => ':attribute är obligatoriskt när ingen av :values är ifyllt.',
    'prohibited' => 'Fältet :attribute är förbjudet.',
    'prohibited_if' => 'Fältet :attribute är förbjudet när :other är :value.',
    'prohibited_unless' => 'Fältet :attribute är förbjudet om inte :other finns i :values.',
    'same' => ':attribute och :other måste vara lika.',
    'size' => [
        'numeric' => ':attribute måste vara :size.',
        'file' => ':attribute får endast vara :size kilobyte stor.',
        'string' => ':attribute måste vara <strong>:size tecken</strong>.',
        'array' => ':attribute måste innehålla :size objekt.',
    ],
    'starts_with' => ':attribute måste börja med något av följande: :values.',
    'string' => ':attribute måste vara en <strong>sträng</strong>.',
    'timezone' => ':attribute måste vara en giltig tidszon.',
    'unique' => ':attribute är redan <strong>taget</strong>.',
    'uploaded' => ':attribute <strong>misslyckades</strong> att ladda upp.',
    'url' => ':attribute formatet är <strong>ogiltigt</strong>.',
    'uuid' => ':attribute måste vara ett giltigt UUID.',

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
            'rule-name' => 'anpassat-meddelande',
        ],
        'invalid_currency'      => 'Attributet :attribute är ogiltig.',
        'invalid_amount'        => 'Beloppet :attribute är ogiltigt.',
        'invalid_extension'     => 'Filtillägget är ogiltigt.',
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
