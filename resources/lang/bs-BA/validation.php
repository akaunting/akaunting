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

    'accepted' => 'Polje :attribute mora biti prihvaćeno.',
    'active_url' => 'Polje :attribute nije validan URL.',
    'after' => 'Polje :attribute mora biti datum poslije :date.',
    'after_or_equal' => ':attribute mora biti datum nakon ili isti kao :date.',
    'alpha' => 'Polje :attribute može sadržati samo slova.',
    'alpha_dash' => 'Polje :attribute može sadržati samo slova, brojeve i povlake.',
    'alpha_num' => 'Polje :attribute može sadržati samo slova i brojeve.',
    'array' => 'Polje :attribute mora biti niz.',
    'before' => 'Polje :attribute mora biti datum prije :date.',
    'before_or_equal' => ':attribute mora biti datum prije ili isti kao :date.',
    'between' => [
        'numeric' => 'Polje :attribute mora biti izmedju :min - :max.',
        'file' => 'Fajl :attribute mora biti izmedju :min - :max kilobajta.',
        'string' => 'Polje :attribute mora biti izmedju :min - :max karaktera.',
        'array' => 'Polje :attribute mora biti između :min - :max karaktera.',
    ],
    'boolean' => 'Polje :attribute mora biti tačno ili netačno.',
    'confirmed' => 'Potvrda polja :attribute se ne poklapa.',
    'current_password' => 'Lozinka nije ispravna',
    'date' => 'Polje :attribute nema ispravan datum.',
    'date_equals' => 'Polje :attribute mora biti datum poslije :date.',
    'date_format' => 'Polje :attribute nema odgovarajući format :format.',
    'different' => 'Polja :attribute i :other moraju biti različita.',
    'digits' => 'Polje :attribute mora da sadži :digits brojeve.',
    'digits_between' => 'Polje :attribute mora biti između :min i :max broja.',
    'dimensions' => ':attribute ima nevažeće dimenzije slike.',
    'distinct' => 'Polje :attribute ima dvostruku vrijednost.',
    'email' => 'Format polja :attribute mora biti validan email.',
    'ends_with' => 'Polje :atribute mora zavrsiti sa jednim od slijedećeg: :values.',
    'exists' => 'Odabrano polje :attribute nije validno.',
    'file' => 'Polje :attribute mora biti fajl.',
    'filled' => 'Polje :attribute je obavezno.',
    'gt' => [
        'numeric' => 'Polje :attribute mora biti veće od :value.',
        'file' => 'Polje :attribute mora biti veće od :value kilobyta.',
        'string' => 'Polje :attribute mora imati veće od :value karaktera.',
        'array' => 'Polje :attribute mora imati više od :value stavki.',
    ],
    'gte' => [
        'numeric' => 'Polje :attribute mora imati više ili jednako od :value.',
        'file' => 'Polje :attribute mora biti više ili jednako od :value kilobyta.',
        'string' => 'Polje :attribute mora biti više ili jednako od :value karaktera.',
        'array' => 'Polje :attribute mora imati :value stavki ili više.',
    ],
    'image' => 'Polje :attribute mora biti slika.',
    'in' => 'Odabrano polje :attribute nije validno.',
    'in_array' => 'Polje :attribute ne postoji u :other.',
    'integer' => 'Polje :attribute mora biti broj.',
    'ip' => 'Polje :attribute mora biti validna IP adresa.',
    'ipv4' => 'Polje :attribute mora biti validna IP adresa.',
    'ipv6' => 'Polje :attribute mora biti validna IPv6 adresa.',
    'json' => ':attribute mora biti valjani JSON niz.',
    'lt' => [
        'numeric' => 'Polje :attribute mora biti manja od :value.',
        'file' => 'Polje :attribute mora biti manja od :value kilobyta.',
        'string' => 'Polje :attribute mora imati manje od :value karaktera.',
        'array' => 'Polje :attribute mora ima manje od :value stavki.',
    ],
    'lte' => [
        'numeric' => 'Polje :attribute mora imati manja ili jednako od :value.',
        'file' => 'Polje :attribute mora biti manje ili jednako od :value kilobyta.',
        'string' => 'Polje :attribute mora biti manje ili jednako od :value karaktera.',
        'array' => 'Polje :attribute mora ima više od :value stavki.',
    ],
    'max' => [
        'numeric' => 'Polje :attribute mora biti manje od :max.',
        'file' => 'Polje :attribute mora biti manje od :max kilobajta.',
        'string' => 'Polje :attribute mora sadržati manje od :max karaktera.',
        'array' => 'Polje :attribute mora sadržati manje od :max karaktera.',
    ],
    'mimes' => 'Polje :attribute mora biti fajl tipa: :values.',
    'mimetypes' => 'Polje :attribute mora biti fajl tipa: :values.',
    'min' => [
        'numeric' => 'Polje :attribute mora biti najmanje :min.',
        'file' => 'Fajl :attribute mora biti najmanje :min kilobajta.',
        'string' => 'Polje :attribute mora sadržati najmanje :min karaktera.',
        'array' => 'Polje :attribute mora sadržati najmanje :min karaktera.',
    ],
    'multiple_of' => 'Polje :attribute mora biti fajl tipa: :values.',
    'not_in' => 'Odabrani element polja :attribute nije validan.',
    'not_regex' => 'Polje :attribute ima neispravan format.',
    'numeric' => 'Polje :attribute mora biti broj.',
    'password' => 'Lozinka nije ispravna',
    'present' => 'Polje :attribute je obavezno.',
    'regex' => 'Polje :attribute ima neispravan format.',
    'required' => 'Polje :attribute je obavezno.',
    'required_if' => 'Polje :attribute je obavezno kada :other je :value.',
    'required_unless' => 'Polje :attribute je obavezno osim ako je :other u :values.',
    'required_with' => 'Polje :attribute je obavezno kada je :values prikazano.',
    'required_with_all' => 'Polje :attribute je obavezno kada je :values prikazano.',
    'required_without' => 'Polje :attribute je obavezno kada :values nije prikazano.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedno :values nije prikazano.',
    'prohibited' => 'Polje :attribute je obavezno.',
    'prohibited_if' => 'Polje :attribute je obavezno kada :other je :value.',
    'prohibited_unless' => 'Polje :attribute je obavezno osim ako je :other u :values.',
    'same' => 'Polja :attribute i :other se moraju poklapati.',
    'size' => [
        'numeric' => 'Polje :attribute mora biti :size.',
        'file' => 'Fajl :attribute mora biti :size kilobajta.',
        'string' => 'Polje :attribute mora biti :size karaktera.',
        'array' => 'Polje :attribute mora biti :size karaktera.',
    ],
    'starts_with' => 'Polje :atribute mora zavrsiti sa jednim od slijedećeg: :values.',
    'string' => 'Polje :attribute mora sadrzavati slova.',
    'timezone' => 'Polje :attribute mora biti ispravna vremenska zona.',
    'unique' => 'Polje :attribute već postoji.',
    'uploaded' => 'Otpremanje :attribute nije uspjelo.',
    'url' => 'Format polja :attribute nije validan.',
    'uuid' => 'Polje :attribute mora biti ispravan UUID.',

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
            'rule-name' => 'prilagođena-poruka',
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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
