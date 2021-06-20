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

    'accepted' => ':attribute musi zostać zaakceptowany.',
    'active_url' => ':attribute jest nieprawidłowym adresem URL.',
    'after' => ':attribute musi być datą późniejszą od :date.',
    'after_or_equal' => ':attribute musi być datą nie wcześniejszą niż :date.',
    'alpha' => ':attribute może zawierać jedynie litery.',
    'alpha_dash' => ':attribute może zawierać jedynie litery, cyfry i myślniki.',
    'alpha_num' => ':attribute może zawierać jedynie litery i cyfry.',
    'array' => ':attribute musi być tablicą.',
    'before' => ':attribute musi być datą wcześniejszą od :date.',
    'before_or_equal' => ':attribute musi być datą nie późniejszą niż :date.',
    'between' => [
        'numeric' => ' :attribute musi zawierać się pomiędzy :min a :max',
        'file' => ':attribute musi zawierać się w granicach :min - :max kilobajtów.',
        'string' => ':attribute musi zawierać się w granicach :min - :max znaków.',
        'array' => ':attribute musi składać się z :min - :max elementów.',
    ],
    'boolean' => ':attribute musi mieć wartość prawda albo fałsz',
    'confirmed' => 'Potwierdzenie :attribute nie zgadza się.',
    'current_password' => 'Hasło jest nieprawidłowe.',
    'date' => ':attribute nie jest prawidłową datą.',
    'date_equals' => ':attribute musi być datą równą :date.',
    'date_format' => ':attribute nie jest w formacie :format.',
    'different' => ':attribute oraz :other muszą się różnić.',
    'digits' => ':attribute musi składać się z :digits cyfr.',
    'digits_between' => ':attribute musi mieć od :min do :max cyfr.',
    'dimensions' => ':attribute ma niepoprawne wymiary.',
    'distinct' => ':attribute ma zduplikowane wartości.',
    'email' => 'Format :attribute jest nieprawidłowy.',
    'ends_with' => ':attribute musi kończyć się jedną z następujących wartości: :values',
    'exists' => 'Zaznaczony :attribute jest nieprawidłowy.',
    'file' => ':attribute musi być plikiem.',
    'filled' => 'Pole :attribute jest wymagane.',
    'gt' => [
        'numeric' => ':attribute musi być większy niż :value.',
        'file' => ':attribute musi być większy niż :value kilobajtów.',
        'string' => ':attribute musi być większy niż :value znaków.',
        'array' => ':attribute musi mieć więcej niż :value elementów.',
    ],
    'gte' => [
        'numeric' => ':attribute musi być większy lub równy :value.',
        'file' => ':attribute musi być większy lub równy :value kilobajtów.',
        'string' => ':attribute musi być większy lub równy :value znaków.',
        'array' => ':attribute musi mieć :value lub więcej.',
    ],
    'image' => ':attribute musi być obrazkiem.',
    'in' => 'Zaznaczony :attribute jest nieprawidłowy.',
    'in_array' => ':attribute nie znajduje się w :other.',
    'integer' => ':attribute musi być liczbą całkowitą.',
    'ip' => ':attribute musi być prawidłowym adresem IP.',
    'ipv4' => ':attribute musi być prawidłowym adresem IPv4.',
    'ipv6' => ':attribute musi być prawidłowym adresem IPv6.',
    'json' => ':attribute musi być poprawnym ciągiem znaków JSON.',
    'lt' => [
        'numeric' => ':attribute musi być mniejszy niż :value.',
        'file' => ':attribute musi być mniejszy niż :value kilobajtów.',
        'string' => ':attribute musi być mniejszy niż :value znaków.',
        'array' => ':attribute musi mieć mniej niż :value elementów.',
    ],
    'lte' => [
        'numeric' => ':attribute musi być mniejszy lub równy :value.',
        'file' => ':attribute musi być mniejszy lub równy :value kilobajtów.',
        'string' => ':attribute musi być mniejszy lub równy :value znaków.',
        'array' => ':attribute nie może mieć więcej niż :value elementów.',
    ],
    'max' => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file' => ':attribute nie może być większy niż :max kilobajtów.',
        'string' => ':attribute nie może być dłuższy niż :max znaków.',
        'array' => ':attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes' => ':attribute musi być plikiem typu :values.',
    'mimetypes' => ':attribute musi być plikiem typu :values.',
    'min' => [
        'numeric' => ':attribute musi być nie mniejszy od :min.',
        'file' => ':attribute musi mieć przynajmniej :min kilobajtów.',
        'string' => ':attribute musi mieć przynajmniej :min znaków.',
        'array' => ':attribute musi mieć przynajmniej :min elementów.',
    ],
    'multiple_of' => ':attribute musi być wielokrotnością :value.',
    'not_in' => 'Zaznaczony :attribute jest nieprawidłowy.',
    'not_regex' => 'Format :attribute jest nieprawidłowy.',
    'numeric' => ':attribute musi być liczbą.',
    'password' => 'Hasło jest nieprawidłowe.',
    'present' => 'Pole :attribute musi być obecne.',
    'regex' => 'Format :attribute jest nieprawidłowy.',
    'required' => 'Pole :attribute jest wymagane.',
    'required_if' => 'Pole :attribute jest wymagane gdy :other jest :value.',
    'required_unless' => ':attribute jest wymagany jeżeli :other nie znajduje się w :values.',
    'required_with' => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_with_all' => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_without' => 'Pole :attribute jest wymagane gdy :values nie jest obecny.',
    'required_without_all' => 'Pole :attribute jest wymagane gdy żadne z :values nie są obecne.',
    'prohibited' => 'Pole :attribute jest zabronione.',
    'prohibited_if' => 'Pole :attribute jest zabronione, gdy :other to :value.',
    'prohibited_unless' => 'Pole :attribute jest zabronione, chyba że :other znajduje się w :values.',
    'same' => 'Pole :attribute i :other muszą się zgadzać.',
    'size' => [
        'numeric' => ':attribute musi mieć :size.',
        'file' => ':attribute musi mieć :size kilobajtów.',
        'string' => ':attribute musi mieć :size znaków.',
        'array' => ':attribute musi zawierać :size elementów.',
    ],
    'starts_with' => ':attribute musi zaczynać się od jednego z następujących elementów: :values.',
    'string' => ':attribute musi być ciągiem znaków.',
    'timezone' => ':attribute musi być prawidłową strefą czasową.',
    'unique' => 'Taki :attribute już występuje.',
    'uploaded' => 'Nie udało się wgrać pliku :attribute.',
    'url' => 'Format :attribute jest nieprawidłowy.',
    'uuid' => ':attribute musi być prawidłowym UUID.',

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
            'rule-name' => 'niestandardowy-komunikat',
        ],
        'invalid_currency'      => 'Kod :attribute jest nieprawidłowy.',
        'invalid_amount'        => 'Kwota :attribute jest nieprawidłowa.',
        'invalid_extension'     => 'Rozszerzenie pliku jest nieprawidłowe.',
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
