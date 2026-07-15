<?php

return [

    /*
|--------------------------------------------------------------------------
|Validation Language Lines
|--------------------------------------------------------------------------
|
|The following language lines contain the default error messages used by
|the validator class. Some of these rules have multiple versions such
|as the size rules. Feel free to tweak each of these messages here.
|
    */

    'accepted' => ':attribute moet worden geaccepteerd.',
    'accepted_if' => 'Het :attribute moet worden aanvaard wanneer :other is :value.',
    'active_url' => ':attribute is geen geldige URL.',
    'after' => ':attribute moet een datum zijn die later is dan :date.',
    'after_or_equal' => ':attribute moet een datum zijn die later is dan :date.',
    'alpha' => ':attribute mag enkel letters bevatten.',
    'alpha_dash' => ':attribute mag enkel letters, cijfers of koppeltekens bevatten.',
    'alpha_num' => ':attribute mag enkel letters en cijfers bevatten.',
    'array' => ':attribute moet geselecteerde elementen bevatten.',
    'before' => ':attribute moet een datum zijn voor :date.',
    'before_or_equal' => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'between' => [
        'array' => 'De kenmerk :attribute moeten tussen :min en :max items zijn.',
        'file' => 'De kenmerk :attribute moet tussen :min en :max kilobytes zijn.',
        'numeric' => 'De kenmerk :attribute moet tussen :min en :max zijn.',
        'string' => 'De kenmerk :attribute moet tussen :min en :max tekens zijn.',
    ],
    'boolean' => 'De kenmerksveld :attribute moet waar of onwaar zijn.',
    'confirmed' => 'De kenmerken :attribute komen niet overeen.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => ':attribute is geen geldige datum.',
    'date_equals' => ':attribute moet een datum gelijk zijn aan :date.',
    'date_format' => ':attribute komt niet overeen met het volgende formaat :format.',
    'declined' => 'Het :attribute moet worden afgewezen.',
    'declined_if' => 'Het :attribute moet worden geweigerd wanneer :other is :value.',
    'different' => ':attribute en :other moeten verschillend zijn.',
    'digits' => ':attribute moet bestaan uit :digits cijfers.',
    'digits_between' => ':attribute moet tussen de :min en :max aantal karakters lang zijn.',
    'dimensions' => ':attribute heeft ongeldige afbeelding afmetingen.',
    'distinct' => ':attribute velden bevat dubbele waarden.',
    'doesnt_start_with' => 'Het :attribute mag niet beginnen met een van de volgende: :values.',
    'double' => ':attribute moet een geldige dubbele waarde zijn.',
    'email' => ':attribute is geen geldig e-mailadres.',
    'ends_with' => ':attribute moet met één van de volgende waarden eindigen: :values.',
    'enum' => 'Het geselecteerde :attribute is ongeldig.',
    'exists' => 'Het geselecteerde kenmerk :attribute is ongeldig.',
    'file' => ':attribute moet een bestand zijn.',
    'filled' => ':attribute is verplicht.',
    'gt' => [
        'array' => ':attribute moet meer dan :value items bevatten.',
        'file' => ':attribute moet groter zijn dan :value kilobytes.',
        'numeric' => ':attribute moet groter zijn dan :value.',
        'string' => ':attribute moet meer dan :value karakters bevatten.',
    ],
    'gte' => [
        'array' => ':attribute moet :value items of meer bevatten.',
        'file' => ':attribute moet groter of gelijk zijn aan :value kilobytes.',
        'numeric' => ':attribute moet groter of gelijk zijn aan :value.',
        'string' => ':attribute moet :value karakters of meer bevatten.',
    ],
    'image' => ':attribute moet een afbeelding zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => ':attribute veld bestaat niet in :other.',
    'in_detailed' => 'De :attribute waarde “:value” is ongeldig. Verwacht werd een van: :values',
    'integer' => ':attribute moet een geheel getal zijn.',
    'ip' => ':attribute moet een geldig IP-adres zijn.',
    'ipv4' => ':attribute moet een geldig IPv4-adres zijn.',
    'ipv6' => ':attribute moet een geldig IPv6-adres zijn.',
    'json' => ':attribute moet een geldige JSON string zijn.',
    'lt' => [
        'array' => ':attribute moet minder dan :value items bevatten.',
        'file' => ':attribute moet kleiner zijn dan :value kilobytes.',
        'numeric' => ':attribute moet kleiner zijn dan :value.',
        'string' => ':attribute moet minder dan :value karakters bevatten.',
    ],
    'lte' => [
        'array' => ':attribute mag niet meer dan :value items bevatten.',
        'file' => ':attribute moet kleiner of gelijk zijn aan :value kilobytes.',
        'numeric' => ':attribute moet kleiner of gelijk zijn aan :value.',
        'string' => ':attribute moet :value karakters of minder bevatten.',
    ],
    'mac_address' => 'Het :attribute moet een geldig MAC-adres zijn.',
    'max' => [
        'array' => ':attribute mag niet meer dan :max items zijn.',
        'file' => ':attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'string' => ':attribute mag niet meer zijn dan :max tekens.',
    ],
    'mimes' => ':attribute moet een bestandstype :values zijn.',
    'mimetypes' => ':attribute moet bestandstype :values zijn.',
    'min' => [
        'array' => ':attribute moet minstens :min items zijn.',
        'file' => ':attribute moet minstens :min kilobytes zijn.',
        'numeric' => ':attribute moet minstens :min zijn.',
        'string' => ':attribute moet minstens :min tekens zijn.',
    ],
    'multiple_of' => ':attribute moet een veelvoud van :value zijn.',
    'not_in' => 'De geselecteerde kenmerk :attribute is ongeldig.',
    'not_regex' => ':attribute formaat is ongeldig.',
    'numeric' => ':attribute moet een getal zijn.',
    'password' => [
        'letters' => 'Het :attribute moet ten minste één letter bevatten.',
        'mixed' => 'Het :attribute moet ten minste één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het :attribute moet ten minste één getal bevatten.',
        'symbols' => 'Het :attribute moet ten minste één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribute is verschenen in een datalek. Kies een ander :attribute.',
    ],
    'present' => ':attribute moet bestaan.',
    'prohibited' => ':attribute veld is verboden.',
    'prohibited_if' => ':attribute veld is verboden wanneer :other :value is.',
    'prohibited_unless' => ':attribute veld is verboden tenzij :other gelijk is aan :values.',
    'prohibits' => 'Het :attribute veld verbiedt :other aanwezig te zijn.',
    'regex' => ':attribute formaat is ongeldig.',
    'required' => ':attribute is verplicht.',
    'required_array_keys' => 'Het :attribute veld moet vermeldingen bevatten voor: :values.',
    'required_if' => ':attribute veld is verplicht wanneer :other :value is.',
    'required_unless' => ':attribute veld is verplicht tenzij :other bestaat in :values.',
    'required_with' => ':attribute veld is verplicht wanneer :values aanwezig is.',
    'required_with_all' => ':attribute is verplicht i.c.m. :values.',
    'required_without' => ':attribute is verplicht als :values niet ingevuld is.',
    'required_without_all' => ':attribute is verplicht als :values niet ingevuld zijn.',
    'same' => ':attribute en :other moeten overeenkomen.',
    'size' => [
        'array' => ':attribute moet :size items bevatten.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'numeric' => ':attribute moet :size zijn.',
        'string' => ':attribute moet :size tekens zijn.',
    ],
    'starts_with' => ':attribute moet beginnen met een van de volgende :values.',
    'string' => ':attribute moet een tekst zijn.',
    'timezone' => ':attribute moet een geldige zone zijn.',
    'unique' => ':attribute is al in gebruik.',
    'uploaded' => 'Het uploaden van :attribute is mislukt.',
    'url' => ':attribute moet een geldige URL zijn.',
    'uuid' => ':attribute moet een geldige UUID zijn.',

    /*
|--------------------------------------------------------------------------
|Custom Validation Language Lines
|--------------------------------------------------------------------------
|
|Here you may specify custom validation messages for attributes using the
|convention "attribute.rule" to name the lines. This makes it quick to
|specify a specific custom language line for a given attribute rule.
|
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'aangepast bericht',
        ],
        'invalid_currency'      => ':attribute code is ongeldig.',
        'invalid_amount'        => 'De hoeveelheid :attribute is ongeldig.',
        'invalid_quantity'      => ':attribute is geen geldige wiskundige uitdrukking.',
        'invalid_extension'     => 'De bestandsextensie is ongeldig.',
        'invalid_dimension'     => 'De :attribute afmetingen moeten max :width x :height px zijn.',
        'invalid_colour'        => 'Het :attribute kleur is ongeldig.',
        'invalid_payment_method'=> 'De betaalmethode is ongeldig.',
    ],

    /*
|--------------------------------------------------------------------------
|Custom Validation Attributes
|--------------------------------------------------------------------------
|
|The following language lines are used to swap our attribute placeholder
|with something more reader friendly such as "E-Mail Address" instead
|of "email". This simply helps us make our message more expressive.
|
    */

    'attributes' => [],

];
