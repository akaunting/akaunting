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

    'accepted'             => 'Polje :attribute mora biti prihvaćeno.',
    'active_url'           => 'Polje :attribute nije validan URL.',
    'after'                => 'Polje :attribute mora biti datum poslije :date.',
    'after_or_equal'       => ':attribute mora biti datum nakon ili isti kao :date.',
    'alpha'                => 'Polje :attribute može sadržati samo slova.',
    'alpha_dash'           => 'Polje :attribute može sadržati samo slova, brojeve i povlake.',
    'alpha_num'            => 'Polje :attribute može sadržati samo slova i brojeve.',
    'array'                => 'Polje :attribute mora biti niz.',
    'before'               => 'Polje :attribute mora biti datum prije :date.',
    'before_or_equal'      => ':attribute mora biti datum prije ili isti kao :date.',
    'between'              => [
        'numeric' => 'Polje :attribute mora biti izmedju :min - :max.',
        'file'    => 'Fajl :attribute mora biti izmedju :min - :max kilobajta.',
        'string'  => 'Polje :attribute mora biti izmedju :min - :max karaktera.',
        'array'   => 'Polje :attribute mora biti između :min - :max karaktera.',
    ],
    'boolean'              => 'Polje :attribute mora biti tačno ili netačno.',
    'confirmed'            => 'Potvrda polja :attribute se ne poklapa.',
    'date'                 => 'Polje :attribute nema ispravan datum.',
    'date_format'          => 'Polje :attribute nema odgovarajući format :format.',
    'different'            => 'Polja :attribute i :other moraju biti različita.',
    'digits'               => 'Polje :attribute mora da sadži :digits brojeve.',
    'digits_between'       => 'Polje :attribute mora biti između :min i :max broja.',
    'dimensions'           => ':attribute ima nevažeće dimenzije slike.',
    'distinct'             => 'Polje :attribute ima dvostruku vrijednost.',
    'email'                => 'Format polja :attribute mora biti validan email.',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',
    'exists'               => 'Odabrano polje :attribute nije validno.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'Polje :attribute je obavezno.',
    'image'                => 'Polje :attribute mora biti slika.',
    'in'                   => 'Odabrano polje :attribute nije validno.',
    'in_array'             => 'Polje :attribute ne postoji u :other.',
    'integer'              => 'Polje :attribute mora biti broj.',
    'ip'                   => 'Polje :attribute mora biti validna IP adresa.',
    'json'                 => ':attribute mora biti valjani JSON niz.',
    'max'                  => [
        'numeric' => 'Polje :attribute mora biti manje od :max.',
        'file'    => 'Polje :attribute mora biti manje od :max kilobajta.',
        'string'  => 'Polje :attribute mora sadržati manje od :max karaktera.',
        'array'   => 'Polje :attribute mora sadržati manje od :max karaktera.',
    ],
    'mimes'                => 'Polje :attribute mora biti fajl tipa: :values.',
    'mimetypes'            => 'Polje :attribute mora biti fajl tipa: :values.',
    'min'                  => [
        'numeric' => 'Polje :attribute mora biti najmanje :min.',
        'file'    => 'Fajl :attribute mora biti najmanje :min kilobajta.',
        'string'  => 'Polje :attribute mora sadržati najmanje :min karaktera.',
        'array'   => 'Polje :attribute mora sadržati najmanje :min karaktera.',
    ],
    'not_in'               => 'Odabrani element polja :attribute nije validan.',
    'numeric'              => 'Polje :attribute mora biti broj.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'Polje :attribute ima neispravan format.',
    'required'             => 'Polje :attribute je obavezno.',
    'required_if'          => 'Polje :attribute je obavezno kada :other je :value.',
    'required_unless'      => 'Polje :attribute je obavezno osim ako je :other u :values.',
    'required_with'        => 'Polje :attribute je obavezno kada je :values prikazano.',
    'required_with_all'    => 'Polje :attribute je obavezno kada je :values prikazano.',
    'required_without'     => 'Polje :attribute je obavezno kada :values nije prikazano.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedno :values nije prikazano.',
    'same'                 => 'Polja :attribute i :other se moraju poklapati.',
    'size'                 => [
        'numeric' => 'Polje :attribute mora biti :size.',
        'file'    => 'Fajl :attribute mora biti :size kilobajta.',
        'string'  => 'Polje :attribute mora biti :size karaktera.',
        'array'   => 'Polje :attribute mora biti :size karaktera.',
    ],
    'string'               => 'Polje :attribute mora sadrzavati slova.',
    'timezone'             => 'Polje :attribute mora biti ispravna vremenska zona.',
    'unique'               => 'Polje :attribute već postoji.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'Format polja :attribute nije validan.',

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
            'rule-name'             => 'prilagođena-poruka',
        ],
        'invalid_currency'      => 'Kód :attribute nije valjan.',
        'invalid_amount'        => 'Iznos :atribut nije valjan.',
        'invalid_extension'     => 'Ekstenzija datoteke nije valjana.',
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
