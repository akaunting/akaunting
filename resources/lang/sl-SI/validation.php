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

    'accepted' => ':attribute mora biti sprejet.',
    'accepted_if' => ':attribute mora biti sprejet, kadar je :other :value.',
    'active_url' => ':attribute ni pravilen.',
    'after' => ':attribute mora biti za datumom :date.',
    'after_or_equal' => ':attribute mora biti za ali enak :date.',
    'alpha' => ':attribute lahko vsebuje samo črke.',
    'alpha_dash' => ':attribute lahko vsebuje samo črke, številke in črtice.',
    'alpha_num' => ':attribute lahko vsebuje samo črke in številke.',
    'array' => ':attribute mora biti polje.',
    'before' => ':attribute mora biti pred datumom :date.',
    'before_or_equal' => ':attribute mora biti pred ali enak :date.',
    'between' => [
        'array' => ':attribute mora imeti med :min in :max elementov.',
        'file' => ':attribute mora biti med :min in :max kilobajti.',
        'numeric' => ':attribute mora biti med :min in :max.',
        'string' => ':attribute mora biti med :min in :max znaki.',
    ],
    'boolean' => ':attribute polje mora biti 1 ali 0',
    'confirmed' => ':attribute potrditev se ne ujema.',
    'current_password' => 'Napačno geslo.',
    'date' => ':attribute ni veljaven datum.',
    'date_equals' => ':attribute mora biti datum primerljiv z :date.',
    'date_format' => ':attribute se ne ujema z obliko :format.',
    'declined' => ':attribute je potrebno zavrniti.',
    'declined_if' => ':attribute je potrebno zavrniti, če je :other :value.',
    'different' => ':attribute in :other mora biti drugačen.',
    'digits' => ':attribute mora imeti :digits cifer.',
    'digits_between' => ':attribute mora biti med :min in :max ciframi.',
    'dimensions' => ':attribute ima napačne dimenzije slike.',
    'distinct' => ':attribute je duplikat.',
    'doesnt_start_with' => ':attribute se ne sme začeti z enim od naslednjih: :values.',
    'email' => ':attribute mora biti veljaven e-poštni naslov.',
    'ends_with' => ':attribute se mora zaključiti z enim od: :values',
    'enum' => 'Izbrani :attribute ni veljaven.',
    'exists' => 'izbran :attribute je neveljaven.',
    'file' => ':attribute mora biti datoteka.',
    'filled' => ':attribute mora biti izpolnjen.',
    'gt' => [
        'array' => ':attribute mora imeti več kot :value elementov.',
        'file' => ':attribute mora biti večji od :value kilobajtov.',
        'numeric' => ':attribute mora biti večji od :value.',
        'string' => ':attribute mora biti večji od :value znakov.',
    ],
    'gte' => [
        'array' => ':attribute mora imeti :value ali več predmetov.',
        'file' => ':attribute mora biti večji ali enak kot :value kilobajtov.',
        'numeric' => ':attribute mora biti večji ali enak kot :value.',
        'string' => ':attribute mora biti večji ali enak kot :value znakov.',
    ],
    'image' => ':attribute mora biti slika.',
    'in' => 'izbran :attribute je neveljaven.',
    'in_array' => ':attribute ne obstaja v :other.',
    'integer' => ':attribute mora biti število.',
    'ip' => ':attribute mora biti veljaven IP naslov.',
    'ipv4' => ':attribute mora biti veljaven IPv4 naslov.',
    'ipv6' => ':attribute mora biti veljaven IPv6 naslov.',
    'json' => ':attribute mora biti veljaven JSON tekst.',
    'lt' => [
        'array' => ':attribute mora imeti manj kot :value predmetov.',
        'file' => ':attribute mora biti manjši od :value kilobajtov.',
        'numeric' => ' :attribute mora biti manj kot :value.',
        'string' => ':attribute mora biti manjši kot :value znakov.',
    ],
    'lte' => [
        'array' => ':attribute mora imeti manj ali enako :value predmetov.',
        'file' => ':attribute rabi biti manj ali enak :value kilobajtov.',
        'numeric' => ':attribute rabi biti manj ali enak :value.',
        'string' => ':attribute mora imeti manj ali enako :value znakov.',
    ],
    'mac_address' => ':attribute mora biti veljaven naslov MAC.',
    'max' => [
        'array' => ':attribute ne smejo imeti več kot :max elementov.',
        'file' => ':attribute ne sme biti večje :max kilobajtov.',
        'numeric' => ':attribute ne sme biti večje od :max.',
        'string' => ':attribute ne sme biti večje :max znakov.',
    ],
    'mimes' => ':attribute mora biti datoteka tipa: :values.',
    'mimetypes' => ':attribute mora biti datoteka tipa: :values.',
    'min' => [
        'array' => ':attribute mora imeti vsaj :min elementov.',
        'file' => ':attribute mora imeti vsaj :min kilobajtov.',
        'numeric' => ':attribute mora biti vsaj dolžine :min.',
        'string' => ':attribute mora imeti vsaj :min znakov.',
    ],
    'multiple_of' => ':attribute mora biti večkratnik :value.',
    'not_in' => 'Izbran :attribute je neveljaven.',
    'not_regex' => 'Oblika :attribute ni veljavna.',
    'numeric' => ':attribute mora biti število.',
    'password' => [
        'letters' => ':attribute mora vsebovati vsaj eno črko.',
        'mixed' => ':attribute mora vsebovati vsaj eno veliko in eno malo črko.',
        'numbers' => ':attribute mora vsebovati vsaj eno številko.',
        'symbols' => ':attribute mora vsebovati vsaj en simbol.',
        'uncompromised' => 'Podani :attribute se je pojavil pri uhajanju podatkov. Izberite drug :attribute.',
    ],
    'present' => 'Polje :attribute mora biti prisotno.',
    'prohibited' => 'Polje :attribute je prepovedano.',
    'prohibited_if' => 'Polje :attribute je prepovedano kadar je :other enako :value.',
    'prohibited_unless' => 'Polje :attribute je prepovedano kadar je :other v :value.',
    'prohibits' => 'Polje :attribute prepoveduje prisotnost :other.',
    'regex' => 'Format polja :attribute je neveljaven.',
    'required' => 'Polje :attribute je obvezno.',
    'required_array_keys' => 'Polje :attribute mora vsebovati vnose za: :values.',
    'required_if' => 'Polje :attribute je obvezno, če je :other enak :value.',
    'required_unless' => 'Polje :attribute je obvezno, razen če je :other v :values.',
    'required_with' => 'Polje :attribute je obvezno, če je :values prisoten.',
    'required_with_all' => 'Polje :attribute je obvezno, če so :values prisoten.',
    'required_without' => 'Polje :attribute je obvezno, če :values ni prisoten.',
    'required_without_all' => 'Polje :attribute je obvezno, če :values niso prisotni.',
    'same' => 'Polje :attribute in :other se morata ujemati.',
    'size' => [
        'array' => ':attribute mora vsebovati :size elementov.',
        'file' => ':attribute mora biti :size kilobajtov.',
        'numeric' => ':attribute mora biti :size.',
        'string' => ':attribute mora biti :size znakov.',
    ],
    'starts_with' => ':attribute se rabi začeti z enim od naslednjih: :values.',
    'string' => ':attribute mora biti tekst.',
    'timezone' => ':attribute mora biti časovna cona.',
    'unique' => ':attribute je že zaseden.',
    'uploaded' => 'Nalaganje :attribute ni uspelo.',
    'url' => ':attribute format je neveljaven.',
    'uuid' => ':attribute mora biti veljaven UUID.',

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
            'rule-name' => 'Prilagojeno sporočilo',
        ],
        'invalid_currency'      => ':attribute koda je neveljavna.',
        'invalid_amount'        => 'Vrednost :attribute je neveljavna.',
        'invalid_extension'     => 'Končnica datoteke je neveljavna.',
        'invalid_dimension'     => 'Mere :attribute morajo biti največ :width x :height px.',
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
