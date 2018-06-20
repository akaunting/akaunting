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

    'accepted'             => ':attribute moet geaccepteerd worden.',
    'active_url'           => ':attribute is geen geldige URL.',
    'after'                => ':attribute moet een datum zijn later dan :date.',
    'after_or_equal'       => ':attribute moet een datum zijn later dan :date.',
    'alpha'                => ':attribute mag enkel letters bevatten.',
    'alpha_dash'           => ':attribute mag enkel letters, cijfers of koppeltekens bevatten.',
    'alpha_num'            => ':attribute mag enkel letters en cijfers bevatten.',
    'array'                => ':attribute moet een string zijn.',
    'before'               => ':attribute moet een datum zijn voor :date.',
    'before_or_equal'      => ':attribute moet een datum zijn voor :date.',
    'between'              => [
        'numeric' => 'De: kenmerk moet tussen: min en: max.',
        'file'    => 'De: kenmerk moet tussen: min en: max kilobytes.',
        'string'  => 'De: kenmerk moet tussen: min en: max tekens.',
        'array'   => 'De: kenmerk moeten tussen: min en: max items.',
    ],
    'boolean'              => 'De: kenmerkveld moet waar of onwaar.',
    'confirmed'            => 'De: kenmerk bevestiging komt niet overeen.',
    'date'                 => 'De: kenmerk is niet een geldige datum.',
    'date_format'          => ':attribute komt niet overeen met het volgende formaat :format.',
    'different'            => 'De: kenmerk en: andere mag niet hetzelfde zijn.',
    'digits'               => 'De: kenmerk moet: cijfers cijfers.',
    'digits_between'       => ':attribute moet tussen de :min en :max aantal karakters lang zijn.',
    'dimensions'           => 'De: kenmerk heeft ongeldige afbeelding afmetingen.',
    'distinct'             => 'De: kenmerkveld is een dubbele waarde.',
    'email'                => 'De :attribute moet een geldig e-mailadres.',
    'exists'               => 'Het geselecteerde kenmerk :attribute is ongeldig.',
    'file'                 => 'De: kenmerk moet een bestand.',
    'filled'               => 'De: kenmerkveld moet een waarde hebben.',
    'image'                => 'De: kenmerk moet een afbeelding zijn.',
    'in'                   => 'De geselecteerde: kenmerk is ongeldig.',
    'in_array'             => 'De: kenmerkveld bestaat niet: andere.',
    'integer'              => 'Het :attribute moet uniek zijn.',
    'ip'                   => 'De: kenmerk moet een geldig IP-adres.',
    'json'                 => 'De: kenmerk moet een geldige JSON-tekenreeks.',
    'max'                  => [
        'numeric' => 'De: kenmerk kan niet groter zijn dan: max.',
        'file'    => 'De: kenmerk kan niet groter zijn dan: max kilobytes.',
        'string'  => 'De: kenmerk kan niet groter zijn dan: max tekens.',
        'array'   => 'De: kenmerk wellicht niet meer dan: max items.',
    ],
    'mimes'                => 'De: kenmerk moet een bestand van het type:: waarden.',
    'mimetypes'            => 'De: kenmerk moet een bestand van het type:: waarden.',
    'min'                  => [
        'numeric' => 'De: kenmerk moet ten minste: min.',
        'file'    => 'De: kenmerk moet ten minste: min kilobytes.',
        'string'  => 'De: kenmerk moet ten minste: min-tekens.',
        'array'   => 'De: kenmerk moet ten minste beschikken over: min punten.',
    ],
    'not_in'               => 'De geselecteerde: kenmerk is ongeldig.',
    'numeric'              => 'De: kenmerk moet een getal zijn.',
    'present'              => 'De: kenmerkveld aanwezig moet zijn.',
    'regex'                => 'De: indeling van het kenmerk is ongeldig.',
    'required'             => 'De: kenmerkveld is verplicht.',
    'required_if'          => 'De: kenmerkveld is vereist wanneer: andere is: waarde.',
    'required_unless'      => ':attribute musí být vyplněno dokud :other je v :values.',
    'required_with'        => 'De: kenmerkveld is vereist wanneer: waarden aanwezig is.',
    'required_with_all'    => 'De: kenmerkveld is vereist wanneer: waarden aanwezig is.',
    'required_without'     => 'De: kenmerkveld is vereist wanneer: waarden niet aanwezig is.',
    'required_without_all' => 'De: kenmerkveld is vereist wanneer geen van: waarden aanwezig zijn.',
    'same'                 => 'De: kenmerk en: andere moet overeenkomen.',
    'size'                 => [
        'numeric' => 'De :attribute moet :size.',
        'file'    => 'De: kenmerk moet: grootte van kilobytes.',
        'string'  => 'De: kenmerk moet: het formaat van tekens.',
        'array'   => 'De: kenmerk moet bevatten: het formaat van objecten.',
    ],
    'string'               => 'De: kenmerk moet een tekenreeks zijn.',
    'timezone'             => 'De: kenmerk moet een geldige zone.',
    'unique'               => 'Het veld :attribute is reeds in gebruik.',
    'uploaded'             => 'De: kenmerk mislukt om te uploaden.',
    'url'                  => 'De: indeling van het kenmerk is ongeldig.',

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
            'rule-name' => 'aangepast-bericht',
        ],
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
