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

    'accepted'             => ':attribute moet worden geaccepteerd.',
    'active_url'           => ':attribute is geen geldige URL.',
    'after'                => ':attribute moet een datum zijn die later is dan :date.',
    'after_or_equal'       => ':attribute moet een datum zijn die later is dan :date.',
    'alpha'                => ':attribute mag enkel letters bevatten.',
    'alpha_dash'           => ':attribute mag enkel letters, cijfers of koppeltekens bevatten.',
    'alpha_num'            => ':attribute mag enkel letters en cijfers bevatten.',
    'array'                => ':attribute moet een rangschikking zijn.',
    'before'               => ':attribute moet een datum zijn voor :date.',
    'before_or_equal'      => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'between'              => [
        'numeric' => 'De kenmerk :attribute moet tussen :min en :max zijn.',
        'file'    => 'De kenmerk :attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => 'De kenmerk :attribute moet tussen :min en :max tekens zijn.',
        'array'   => 'De kenmerk :attribute moeten tussen :min en :max items zijn.',
    ],
    'boolean'              => 'De kenmerksveld :attribute moet waar of onwaar zijn.',
    'confirmed'            => 'De kenmerken :attribute komen niet overeen.',
    'date'                 => ':attribute is geen geldige datum.',
    'date_format'          => ':attribute komt niet overeen met het volgende formaat :format.',
    'different'            => ':attribute en :other mag niet hetzelfde zijn.',
    'digits'               => ':attribute moet bestaan uit :digits cijfers.',
    'digits_between'       => ':attribute moet tussen de :min en :max aantal karakters lang zijn.',
    'dimensions'           => ':attribute heeft ongeldige afbeelding afmetingen.',
    'distinct'             => ':attribute velden bevat dubbele waarden.',
    'email'                => ':attribute moet een geldig e-mailadres zijn.',
    'exists'               => 'Het geselecteerde kenmerk :attribute is ongeldig.',
    'file'                 => ':attribute moet een bestand zijn.',
    'filled'               => ':attribute veld moet een waarde bevatten.',
    'image'                => ':attribute moet een afbeelding zijn.',
    'in'                   => 'Het geselecteerde :attribute is ongeldig.',
    'in_array'             => ':attribute veld bestaat niet in :other.',
    'integer'              => 'Het :attribute moet een getal zijn.',
    'ip'                   => ':attribute moet een geldig IP-adres zijn.',
    'json'                 => ':attribute moet een geldige JSON string zijn.',
    'max'                  => [
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'file'    => ':attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => ':attribute mag niet meer zijn dan :max tekens.',
        'array'   => ':attribute mag niet meer dan :max items zijn.',
    ],
    'mimes'                => ':attribute moet een bestandstype :values zijn.',
    'mimetypes'            => ':attribute moet bestandstype :values zijn.',
    'min'                  => [
        'numeric' => ':attribute moet minstens :min zijn.',
        'file'    => ':attribute moet minstens :min kilobytes zijn.',
        'string'  => ':attribute moet minstens :min tekens zijn.',
        'array'   => ':attribute moet minstens :min items zijn.',
    ],
    'not_in'               => 'De geselecteerde kenmerk :attribute is ongeldig.',
    'numeric'              => ':attribute moet een getal zijn.',
    'present'              => ':attribute moet aanwezig zijn.',
    'regex'                => 'De indeling van kenmerk :attribute is ongeldig.',
    'required'             => ':attribute is verplicht.',
    'required_if'          => ':attribute veld is verplicht wanneer :other :value is.',
    'required_unless'      => ':attribute veld is verplicht tenzij :other bestaat in :values.',
    'required_with'        => ':attribute veld is verplicht wanneer :values aanwezig is.',
    'required_with_all'    => ':attribute veld is verplicht wanneer :values aanwezig is.',
    'required_without'     => ':attribute veld is verplicht wanneer :values niet aanwezig is.',
    'required_without_all' => ':attribute veld is verplicht wanneer geen van :values aanwezig zijn.',
    'same'                 => ':attribute en :other moeten overeenkomen.',
    'size'                 => [
        'numeric' => ':attribute moet :size zijn.',
        'file'    => ':attribute moet :size kilobytes zijn.',
        'string'  => ':attribute moet :size tekens zijn.',
        'array'   => ':attribute moet :size items bevatten.',
    ],
    'string'               => ':attribute moet een string zijn.',
    'timezone'             => ':attribute moet een geldige zone zijn.',
    'unique'               => ':attribute is al reeds in gebruik.',
    'uploaded'             => 'Mislukt om :attribute te uploaden.',
    'url'                  => ':attribute kenmerk is ongeldig.',

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
            'rule-name' => 'aangepast bericht',
        ],
        'invalid_currency' => ':attribute code is ongeldig.',
        'invalid_amount'   => 'De hoeveelheid :attribute is ongeldig.',
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
