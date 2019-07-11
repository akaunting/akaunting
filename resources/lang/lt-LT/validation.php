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

    'accepted'             => ':Attribute turi būti pažymėtas.',
    'active_url'           => ':Attribute nėra galiojantis internetinis adresas.',
    'after'                => ':Attribute reikšmė turi būti po :date datos.',
    'after_or_equal'       => ':Attribute privalo būti data lygi arba vėlesnė už :date.',
    'alpha'                => ':Attribute gali turėti tik raides.',
    'alpha_dash'           => ':Attribute gali turėti tik raides, skaičius ir brūkšnelius.',
    'alpha_num'            => 'Laukas :attribute gali turėti tik raides ir skaičius.',
    'array'                => ':Attribute turi būti masyvas.',
    'before'               => ':Attribute turi būti data prieš :date.',
    'before_or_equal'      => ':Attribute privalo būti data ankstenė arba lygi :date.',
    'between'              => [
        'numeric' => ':Attribute reikšmė turi būti tarp :min ir :max.',
        'file'    => ':Attribute failo dydis turi būti tarp :min ir :max kilobaitų.',
        'string'  => ':Attribute simbolių skaičius turi būti tarp :min ir :max.',
        'array'   => ':Attribute elementų skaičius turi turėti nuo :min iki :max.',
    ],
    'boolean'              => ':Attribute turi būti \'taip\' arba \'ne\'.',
    'confirmed'            => ':Attribute patvirtinimas nesutampa.',
    'date'                 => ':Attribute nėra galiojanti data.',
    'date_format'          => ':Attribute reikšmė neatitinka :format formato.',
    'different'            => 'Laukų :attribute ir :other reikšmės turi skirtis.',
    'digits'               => ':Attribute turi būti sudarytas iš :digits skaitmenų.',
    'digits_between'       => ':Attribute tuti turėti nuo :min iki :max skaitmenų.',
    'dimensions'           => 'Lauke :attribute įkeltas paveiksliukas neatitinka išmatavimų reikalavimo.',
    'distinct'             => 'Laukas :attribute pasikartoja.',
    'email'                => 'Lauko :attribute reikšmė turi būti galiojantis el. pašto adresas.',
    'exists'               => 'Pasirinkta negaliojanti :attribute reikšmė.',
    'file'                 => ':Attribute privalo būti failas.',
    'filled'               => 'Laukas :attribute turi būti užpildytas.',
    'image'                => 'Lauko :attribute reikšmė turi būti paveikslėlis.',
    'in'                   => 'Pasirinkta negaliojanti :attribute reikšmė.',
    'in_array'             => 'Laukas :attribute neegzistuoja :other lauke.',
    'integer'              => 'Lauko :attribute reikšmė turi būti veikasis skaičius.',
    'ip'                   => 'Lauko :attribute reikšmė turi būti galiojantis IP adresas.',
    'json'                 => 'Lauko :attribute reikšmė turi būti JSON tekstas.',
    'max'                  => [
        'numeric' => 'Lauko :attribute reikšmė negali būti didesnė nei :max.',
        'file'    => 'Failo dydis lauke :attribute reikšmė negali būti didesnė nei :max kilobaitų.',
        'string'  => 'Simbolių kiekis lauke :attribute reikšmė negali būti didesnė nei :max simbolių.',
        'array'   => 'Elementų kiekis lauke :attribute negali turėti daugiau nei :max elementų.',
    ],
    'mimes'                => 'Lauko reikšmė :attribute turi būti failas vieno iš sekančių tipų: :values.',
    'mimetypes'            => 'Lauko reikšmė :attribute turi būti failas vieno iš sekančių tipų: :values.',
    'min'                  => [
        'numeric' => 'Lauko :attribute reikšmė turi būti ne mažesnė nei :min.',
        'file'    => 'Failo dydis lauke :attribute turi būti ne mažesnis nei :min kilobaitų.',
        'string'  => 'Simbolių kiekis lauke :attribute turi būti ne mažiau nei :min.',
        'array'   => 'Elementų kiekis lauke :attribute turi būti ne mažiau nei :min.',
    ],
    'not_in'               => 'Pasirinkta negaliojanti reikšmė :attribute.',
    'numeric'              => 'Lauko :attribute reikšmė turi būti skaičius.',
    'present'              => 'Laukas :attribute turi egzistuoti.',
    'regex'                => 'Negaliojantis lauko :attribute formatas.',
    'required'             => 'Privaloma užpildyti lauką :attribute.',
    'required_if'          => 'Privaloma užpildyti lauką :attribute kai :other yra :value.',
    'required_unless'      => 'Laukas :attribute yra privalomas, nebent :other yra tarp :values reikšmių.',
    'required_with'        => 'Privaloma užpildyti lauką :attribute kai pateikta :values.',
    'required_with_all'    => 'Privaloma užpildyti lauką :attribute kai pateikta :values.',
    'required_without'     => 'Privaloma užpildyti lauką :attribute kai nepateikta :values.',
    'required_without_all' => 'Privaloma užpildyti lauką :attribute kai nepateikta nei viena iš reikšmių :values.',
    'same'                 => 'Laukai :attribute ir :other turi sutapti.',
    'size'                 => [
        'numeric' => 'Lauko :attribute reikšmė turi būti :size.',
        'file'    => 'Failo dydis lauke :attribute turi būti :size kilobaitai.',
        'string'  => 'Simbolių skaičius lauke :attribute turi būti :size.',
        'array'   => 'Elementų kiekis lauke :attribute turi būti :size.',
    ],
    'string'               => 'Laukas :attribute turi būti tekstinis.',
    'timezone'             => 'Lauko :attribute reikšmė turi būti galiojanti laiko zona.',
    'unique'               => 'Tokia :attribute reikšmė jau pasirinkta.',
    'uploaded'             => 'Nepavyko įkelti :attribute.',
    'url'                  => 'Negaliojantis lauko :attribute formatas.',

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
            'rule-name' => 'Pasirinktinis pranešimas',
        ],
        'invalid_currency' => ':Attribute kodas neteisingas.',
        'invalid_amount'   => ':Attribute kiekis yra neteisingas.',
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
