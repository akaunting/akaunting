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

    'accepted'             => ':attribute må aksepteres.',
    'active_url'           => ':attribute er ikke en gyldig nettadresse.',
    'after'                => ':attribute må være en dato etter :date.',
    'after_or_equal'       => ':attribute må være en dato etter eller samme som :date.',
    'alpha'                => ':attribute kan bare inneholde bokstaver.',
    'alpha_dash'           => ':attribute kan bare inneholde bokstaver, tall og bindestreker.',
    'alpha_num'            => ':attribute kan bare inneholde bokstaver og tall.',
    'array'                => ':attribute må være en kommaseparert liste.',
    'before'               => ':attribute må være en dato før :date.',
    'before_or_equal'      => ':attribute må være en dato før eller samme som :date.',
    'between'              => [
        'numeric' => ':attribute må være mellom :min - :max.',
        'file'    => ':attribute må være mellom :min og :max kilobyte.',
        'string'  => ':attribute må inneholde mellom :min og :max tegn.',
        'array'   => ':attribute må inneholde mellom :min og :max elementer.',
    ],
    'boolean'              => 'Feltet :attribute må være \'true\' eller \'false\'.',
    'confirmed'            => 'Bekreftelsen for :attribute samsvarer ikke.',
    'date'                 => ':attribute er ikke en gyldig dato.',
    'date_format'          => ':attribute samsvarer ikke med formatet :format.',
    'different'            => ':attribute og :other må være forskjellige.',
    'digits'               => ':attribute må inneholde :digits sifre.',
    'digits_between'       => ':attribute må inneholde mellom :min og :max sifre.',
    'dimensions'           => ':attribute har feil bildedimensjoner.',
    'distinct'             => ':attribute feltet har en duplisert verdi.',
    'email'                => ':attribute må være en gyldig e-postadresse.',
    'exists'               => 'Det valgte :attribute er ugyldig.',
    'file'                 => ':attribute må være en fil.',
    'filled'               => 'Feltet for :attribute må fylles ut.',
    'image'                => ':attribute må være et bilde.',
    'in'                   => 'Valgt :attribute er ugyldig.',
    'in_array'             => ':attribute felt eksisterer ikke i :other.',
    'integer'              => ':attribute må være et heltall.',
    'ip'                   => ':attribute må være en gyldig IP-adresse.',
    'json'                 => ':attribute må være i gyldig JSON-format.',
    'max'                  => [
        'numeric' => ':attribute kan ikke være større enn :max.',
        'file'    => ':attribute kan ikke være større enn :max kilobytes.',
        'string'  => ':attribute kan ikke være mer enn :max tegn.',
        'array'   => ':attribute kan ikke inneholde mer enn :max elementer.',
    ],
    'mimes'                => ':attribute må være en fil av typen: :values.',
    'mimetypes'            => ':attribute må være en fil av typen: :values.',
    'min'                  => [
        'numeric' => ':attribute må være større enn :min.',
        'file'    => ':attribute må være større enn :min kilobytes.',
        'string'  => ':attribute må inneholde minst :min tegn.',
        'array'   => ':attribute må inneholde minst :min elementer.',
    ],
    'not_in'               => 'Valgt :attribute er ugyldig.',
    'numeric'              => ':attribute må være et nummer.',
    'present'              => 'Feltet :attribute må være tilstede.',
    'regex'                => 'Formatet på :attribute er ugyldig.',
    'required'             => 'Feltet :attribute må fylles ut.',
    'required_if'          => 'Feltet :attribute må fylles ut når :other er :value.',
    'required_unless'      => 'Feltet :attribute er påkrevd med mindre :other finnes blant verdiene :values.',
    'required_with'        => 'Feltet :attribute må fylles ut når :values er utfylt.',
    'required_with_all'    => 'Feltet :attribute er påkrevd når :values er oppgitt.',
    'required_without'     => 'Feltet :attribute må fylles ut når :values ikke er utfylt.',
    'required_without_all' => 'Feltet :attribute er påkrevd når ingen av :values er oppgitt.',
    'same'                 => ':attribute og :other må samsvare.',
    'size'                 => [
        'numeric' => ':attribute må være :size.',
        'file'    => ':attribute må være :size kilobytes.',
        'string'  => ':attribute må inneholde :size tegn.',
        'array'   => ':attribute må inneholde :size elementer.',
    ],
    'string'               => ':attribute må være en tekststreng.',
    'timezone'             => ':attribute må være en gyldig sone.',
    'unique'               => ':attribute er allerede i bruk.',
    'uploaded'             => ':attribute feilet under opplastingen.',
    'url'                  => 'Formatet på :attribute er ugyldig.',

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
        'invalid_currency' => ':attribute koden er feil.',
        'invalid_amount'   => 'Beløpets :attribute er feil.',
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
