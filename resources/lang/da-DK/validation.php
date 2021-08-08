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

    'accepted' => ':attribute skal være accepteret.',
    'active_url' => ':attribute er ikke en gyldig URL.',
    'after' => ':attribute skal være en dato efter :date.',
    'after_or_equal' => ':attribute skal være en dato før eller lig med :date.',
    'alpha' => ':attribute må kun indeholde bogstaver.',
    'alpha_dash' => ':attribute må kun indeholde bogstaver, tal eller bindestreger.',
    'alpha_num' => ':attribute må kun indeholde bogstaver eller tal.',
    'array' => ':attribute skal være en matrix.',
    'before' => ':attribute skal være en dato før :date.',
    'before_or_equal' => ':attribute skal være en dato før eller lig med :date.',
    'between' => [
        'numeric' => ':attribute skal være imellem :min og :max.',
        'file' => ':attribute skal være imellem :min - :max kilobytes.',
        'string' => ':attribute skal være imellem :min - :max tegn.',
        'array' => ':attribute skal have mellem: min og: maks. Emner.',
    ],
    'boolean' => ':attribute skal være enabled eller disabled.',
    'confirmed' => ':attribute valget stemmer ikke overens.',
    'current_password' => 'Adgangskoden er forkert.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_equals' => ':attribute skal være en dato lig med :date.',
    'date_format' => ':attribute svarer ikke til formatet :format.',
    'different' => ':attribute og :other skal være <strong>forskellige</strong>.',
    'digits' => ':attribute skal være :digits cifre.',
    'digits_between' => ':attribute skal være imellem :min og :max cifre.',
    'dimensions' => ':attribute har ugyldige billeddimensioner.',
    'distinct' => ':attribute har en duplikatværdi.',
    'email' => ':attribute skal være en gyldig <strong>email adresse</strong>.',
    'ends_with' => ':attribute skal ende med en af følgende: :values',
    'exists' => 'Det valgte :attribute er ugyldigt.',
    'file' => ':attribute skal være en <strong>fil</strong>.',
    'filled' => ':attribute feltet skal have en <strong>værdi</strong>.',
    'gt' => [
        'numeric' => ':attribute skal være større end :value.
',
        'file' => ':attribute skal være større end :value kilobytes.',
        'string' => ':attribute skal være flere end :value tegn.
',
        'array' => ':attribute skal indeholde mere end :value varer.',
    ],
    'gte' => [
        'numeric' => ':attribute skal være større end eller lig med :value.',
        'file' => ':attribute skal være større end eller lig :value kilobytes.
',
        'string' => ':attribute skal være større end eller lig :value karakterer.',
        'array' => ':attribute skal have :value varer eller mere.',
    ],
    'image' => ':attribute skal være et <strong>billede</strong>.',
    'in' => 'Det valgte :attribute er ugyldigt.',
    'in_array' => ':attribute findes ikke i :other.',
    'integer' => ':attribute skal være et <strong>heltal</strong>.',
    'ip' => ':attribute skal være en gyldig IP adresse.',
    'ipv4' => ':attribute skal være en valid IPv4 adresse',
    'ipv6' => ':attribute skal være en valid IPv6 adresse',
    'json' => ':attribute skal være en gyldig JSON-streng.',
    'lt' => [
        'numeric' => ':attribute skal være mindre end :value.
',
        'file' => ':attribute skal være mindre end :value kilobytes.',
        'string' => ':attribute skal være færre end :value tegn.
',
        'array' => ':attribute skal indeholde mindre end :value varer.',
    ],
    'lte' => [
        'numeric' => ':attribute skal være mindre end eller lig med :value.',
        'file' => ':attribute skal være mindre end eller lig :value kilobytes.
',
        'string' => ':attribute skal være færre end eller lig :value karakterer.',
        'array' => ':attribute skal indeholde færre end :value varer.',
    ],
    'max' => [
        'numeric' => ':attribute må ikke overstige :max.',
        'file' => ':attribute må ikke overstige :max. kilobytes.',
        'string' => ':attribute må ikke overstige :max. tegn.',
        'array' => ':attribute må ikke overstige :max. antal.',
    ],
    'mimes' => ':attribute skal være en fil af typen: :values.',
    'mimetypes' => ':attribute skal være en fil af typen: :values.',
    'min' => [
        'numeric' => ':attribute skal mindst være :min.',
        'file' => ':attribute skal mindst være :min kilobytes.',
        'string' => ':attribute skal mindst være :min tegn.',
        'array' => ':attribute skal have mindst :min styk.',
    ],
    'multiple_of' => ':attribute skal være en multipel af :values.',
    'not_in' => 'Det valgte :attribute er ugyldigt.',
    'not_regex' => ':attribute formatet er forkert.',
    'numeric' => ':attribute skal være et tal.',
    'password' => 'Adgangskoden er forkert.',
    'present' => ':attribute feltet skal være <strong>til stede</strong>.',
    'regex' => ':attribute formatet er <strong>forkert</strong>.',
    'required' => ':attribute feltet er <strong>påkrævet</strong>.',
    'required_if' => ':attribute feltet er krævet når :other er :value.',
    'required_unless' => ':attribute feltet er påkrævet, med mindre :other er :value.',
    'required_with' => ':attribute er påkrævet, når :values er til stede.',
    'required_with_all' => ':attribute er påkrævet, når :values er til stede.',
    'required_without' => ':attribute er påkrævet, når :values ikke er tilstede.',
    'required_without_all' => ':attribute er påkrævet, når ingen af :values er tilstede.',
    'prohibited' => ':attribute feltet er ikke tilladt.',
    'prohibited_if' => ':attribute feltet er ikke tilladt når :other er :value.',
    'prohibited_unless' => ':attribute feltet er ikke tilladt, med mindre :other er :value.',
    'same' => ':attribute og :other skal være ens.',
    'size' => [
        'numeric' => ':attribute skal være :size.',
        'file' => ':attribute skal være :size kilobytes.',
        'string' => ':attribute skal være <strong>:size karakterer</strong>.',
        'array' => ':attribute skal indeholde :size antal.',
    ],
    'starts_with' => ':attribute skal starte med en af følgende: :values',
    'string' => ':attribute skal være en <strong>tekst</strong>.',
    'timezone' => ':attribute skal være en gyldig zone.',
    'unique' => ':attribute er allerede <strong>taget</strong>.',
    'uploaded' => ':attribute <strong>kunne ikke</strong> uploades.',
    'url' => ':attribute formatet er <strong>ugyldigt</strong>.',
    'uuid' => ':attribute skal være en gyldig UUID.',

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
            'rule-name' => 'brugerdefineret besked',
        ],
        'invalid_currency'      => 'Koden :attribute er ugyldig.',
        'invalid_amount'        => 'Det valgte :attribute er ugyldigt.',
        'invalid_extension'     => 'Filendelsen er ugyldig.',
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
