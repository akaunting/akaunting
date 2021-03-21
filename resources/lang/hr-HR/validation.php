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

    'accepted'             => ':attribute mora biti prihvaćeno.',
    'active_url'           => ':attribute nije važeći URL.',
    'after'                => ':attribute mora biti datum nakon :date.',
    'after_or_equal'       => ':attribute mora biti datum nakon ili isti kao :date.',
    'alpha'                => ':attribute može sadržavati samo slova.',
    'alpha_dash'           => ':attribute može sadržavati samo slova, brojeve i crtice.',
    'alpha_num'            => ':attribute može sadržavati samo slova i brojeve.',
    'array'                => ':attribute mora biti polje.',
    'before'               => ':attribute mora biti datum prije :date.',
    'before_or_equal'      => ':attribute mora biti datum prije ili isti kao :date.',
    'between'              => [
        'numeric' => ':attribute mora biti između :min i :max.',
        'file'    => ':attribute mora biti između :min i :max kilobajta.',
        'string'  => ':attribute mora biti između :min i :max znakova.',
        'array'   => ':attribute mora imati između :min i :max stavki.',
    ],
    'boolean'              => 'Polje :attribute mora biti true ili false.',
    'confirmed'            => ':attribute potvrda se ne podudara.',
    'date'                 => ':attribute nije važeći datum.',
    'date_format'          => ':attribute ne odgovara formatu :format.',
    'different'            => 'Polja :attribute i :other moraju biti različita.',
    'digits'               => ':attribute mora sadržavati :digits znamenki.',
    'digits_between'       => ':attribute mora biti između :min i :max znamenki.',
    'dimensions'           => ':attribute ima nevažeće dimenzije slike.',
    'distinct'             => 'Polje :attribute ima dvostruku vrijednost.',
    'email'                => 'Polje :attribute mora biti ispravna e-mail adresa.',
    'ends_with'            => ':atribut mora završiti jednim od sljedećeg:: vrijednosti',
    'exists'               => 'Odabrano :attribute nije važeće.',
    'file'                 => 'Polje :attribute mora biti datoteka.',
    'filled'               => 'Polje :attribute je obavezno.',
    'image'                => 'Polje :attribute mora biti slika.',
    'in'                   => 'Odabrano :attribute nije valjano.',
    'in_array'             => 'Polje :attribute ne postoji u :other.',
    'integer'              => 'Polje :attribute mora biti broj.',
    'ip'                   => ':attribute mora biti važeća IP adresa.',
    'json'                 => ':attribute mora biti valjani JSON niz.',
    'max'                  => [
        'numeric' => ':attribute ne može biti veće od :max.',
        'file'    => ':attribute ne može biti veće od :max kilobajta.',
        'string'  => ':attribute ne može biti više od :max znakova.',
        'array'   => ':attribute ne može imati više od :max stavki.',
    ],
    'mimes'                => ':attribute mora biti datoteka tipa: :values.',
    'mimetypes'            => ':attribute mora biti datoteka tipa: :values.',
    'min'                  => [
        'numeric' => ':attribute mora biti barem :min.',
        'file'    => ':attribute mora biti barem :min kilobajta.',
        'string'  => ':attribute mora sadržavati barem :min znakova.',
        'array'   => ':attribute mora sadržavati barem :min stavki.',
    ],
    'not_in'               => 'Odabrano :attribute nije valjano.',
    'numeric'              => ':attribute mora biti broj.',
    'present'              => 'Polje :attribute mora biti prisutno.',
    'regex'                => 'Polje :attribute se ne podudara s formatom.',
    'required'             => 'Polje :attribute je obavezno.',
    'required_if'          => 'Polje :attribute je obavezno kada je :other :value.',
    'required_unless'      => 'Polje :attribute je obavezno osim ako je :other u :values.',
    'required_with'        => 'Polje :attribute je obavezno kada postoje polja :values.',
    'required_with_all'    => 'Polje :attribute je obavezno kada postoje polja :values.',
    'required_without'     => 'Polje :attribute je obavezno kada ne postoji polje :values.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedno od polja :values ne postoji.',
    'same'                 => ':attribute i :other se moraju podudarati.',
    'size'                 => [
        'numeric' => ':attribute mora biti :size.',
        'file'    => ':attribute mora biti :size kilobajta.',
        'string'  => 'Polje :attribute mora biti :size znakova.',
        'array'   => ':attribute mora sadržavati :size stavki.',
    ],
    'string'               => 'Polje :attribute mora biti string.',
    'timezone'             => ':attribute mora biti važeća vremenska zona.',
    'unique'               => 'Polje :attribute već postoji.',
    'uploaded'             => 'Polje :attribute nije uspešno učitano.',
    'url'                  => 'Polje :attribute nije ispravnog formata.',

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
        'invalid_currency'      => ': Atributni kod nije valjan.',
        'invalid_amount'        => 'Iznos: atribut nije važeći.',
        'invalid_extension'     => 'The file extension is invalid.',
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
