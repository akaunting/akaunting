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

    'accepted' => ' :attribute ir jābūt pieņemtam.',
    'accepted_if' => ' :attribute jāpieņem, kad :other ir :value.',
    'active_url' => ' :attribute ir ar nederīgu linku.',
    'after' => ' :attribute ir jābūt ar datumu pēc :datums.',
    'after_or_equal' => ' :attribute ir jābūt ar datumu pēc vai vienādu ar :datums.',
    'alpha' => ' :attribute var saturēt tikai burtus.',
    'alpha_dash' => ' :attribute var saturēt tikai burtus, nummurus un atstarpes.',
    'alpha_num' => ' :attribute var tikai saturēt burtus un nummurus.',
    'array' => ' :attribute ir jābūt sakārtotam.',
    'before' => ' :attribute ir jābūt ar datumu pirms :datums.',
    'before_or_equal' => ' :attribute ir jābūt ar datumu pirms vai vienādu ar :datums.',
    'between' => [
        'array' => ' :attribute jābūt no :min līdz :max vienībām.',
        'file' => ' :attribute jābūt starp :min un :max kilobaiti.',
        'numeric' => ' :attribute jābūt starp :min un :max.',
        'string' => ' :attribute jābūt no :min līdz :max zīmēm.',
    ],
    'boolean' => ' :attribute laiciņam jābūt atbilstošam vai neatbilstošam.',
    'confirmed' => ' :attribute apstiprinājums neatbilst.',
    'current_password' => 'Jūsu parole nav pareiza.',
    'date' => ' :attribute nav derīgs.',
    'date_equals' => ' :attribute ir jābūt ar datumu kas vienāds :date.',
    'date_format' => ' :attribute neatbilst formātam :format.',
    'declined' => ':attribute ir jānoraida.',
    'declined_if' => ':attribute ir jānoraida, kad :other ir :value.',
    'different' => ' :attribute un :other ir jābūt citiem.',
    'digits' => ' :attribute ir jābūt :digits ciparam.',
    'digits_between' => ' :attribute ir jābūt :min un :max ciparam.',
    'dimensions' => ' :attribute ir nederīgs attēla izmērs.',
    'distinct' => ' :attribute laikam ir dubulta vērtība.',
    'doesnt_start_with' => ':attribute nedrīkst sākties ar kādu no tālāk norādītajiem: :values.',
    'email' => ' :attribute derīgam e-pastam.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'enum' => 'Atlasītais :attribute nav derīgs.',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'file' => ' :attribute jābūt failam.',
    'filled' => ':attribute lauks ir nepieciešams.',
    'gt' => [
        'array' => ' :attribute jābūt lielākai kā :value vienībai.',
        'file' => ' :attribute jābūt lielākam par :max kilobaiti.',
        'numeric' => ' :attribute jābūt lielākam par :value.',
        'string' => ' :attribute jābūt lielākam par :value rakstzīmēm.',
    ],
    'gte' => [
        'array' => ':attribute jābūt :value vienumi vai vairāk.',
        'file' => ' :attribute jābūt lielākai vai vienādai ar :max kilobaitiem.',
        'numeric' => ' :attribute jābūt lielākam vai vienādam ar :value.',
        'string' => ' :attribute jābūt lielākam vai vienādam ar :value rakstzīmēm.',
    ],
    'image' => ' :attribute jābūt attēlam.',
    'in' => 'Izvēlētais :attribute ir nederīgs.',
    'in_array' => ' :attribute laiks neeksistē :cits.',
    'integer' => ' :attribute ir jabūt skaitim.',
    'ip' => ' :attribute jābūt derīgai IP adresei.',
    'ipv4' => ':attribute jābūt derīgai IPv4 adresei.',
    'ipv6' => ' :attribute jābūt derīgai IPv6 adresei.',
    'json' => ' :attribute jābūt derīgai JSON virknei.',
    'lt' => [
        'array' => 'The :attribute jābūt mazākam par :value vienībām.',
        'file' => 'The :attribute jābūt mazākam par :value kilobaitiem.',
        'numeric' => ':attribute jābūt mazākam par :value.',
        'string' => ' :attribute jābūt mazākam par :value rakstzīmēm.',
    ],
    'lte' => [
        'array' => 'The :attribute nedrīkst būt vairāk kā :value vienībām.',
        'file' => ' :attribute jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'numeric' => ' :attribute jābūt mazākam vai vienādam ar :value.',
        'string' => ' :attribute jābūt mazākam vai vienādam ar :value rakstzīmēm.',
    ],
    'mac_address' => ':attribute ir jābūt derīgai MAC adresei.',
    'max' => [
        'array' => ' :attribute nedrīkst pārsniegt :max vienības.',
        'file' => ' :attribute nedrīkst pārsniegt :max kilobaiti.',
        'numeric' => ' :attribute nedrīkst pārsniegt :max.',
        'string' => ' :attribute nedrīkst pārsniegt :max zīmes.',
    ],
    'mimes' => ' :attribute jābūt faila tipam: :values',
    'mimetypes' => ' :attribute jābūt faile tipam: :values.',
    'min' => [
        'array' => ' :attribute jāsatur vismaz :min vienības.',
        'file' => ' :attribute jābūt vismaz :min kilobaiti.',
        'numeric' => ' :attribute jābūt vismaz :min.',
        'string' => ' :attribute jābūt vismaz :min zīmes.',
    ],
    'multiple_of' => ':attribute jābūt skaitlim :value.',
    'not_in' => ' izvēlieties :attribute ir nederīgs.',
    'not_regex' => ' :attribute formāts ir nederīgs.',
    'numeric' => ' :attribute jābūt skaitlim.',
    'password' => [
        'letters' => ':attribute ir jāsatur vismaz viens burts.',
        'mixed' => ':attribute ir jābūt vismaz vienam lielajam un vienam mazajam burtam.',
        'numbers' => ':attribute ir jāsatur vismaz viens skaitlis.',
        'symbols' => ':attribute ir jāsatur vismaz viens simbols.',
        'uncompromised' => 'Dotais :attribute ir parādījies datu noplūdē. Lūdzu, izvēlieties citu :attribute.',
    ],
    'present' => ' :attribute laikums ir nepieciešams.',
    'prohibited' => ':attribute lauks ir aizliegts.',
    'prohibited_if' => ':attribute lauks ir aizliegts, ja :other ir :value.',
    'prohibited_unless' => ' :attribute lauks ir aizliegts, ja :other ir :values.',
    'prohibits' => 'Lauks :attribute aizliedz :other atrasties.',
    'regex' => ' :attribute formāts ir nederīgs.',
    'required' => ' :attribute laukums ir nepieciešams.',
    'required_array_keys' => 'Laukā :attribute ir jābūt ierakstiem: :values.',
    'required_if' => ' :attribute laukums ir nepieciešams, ja vien :other ir :values.',
    'required_unless' => ' :attribute laukums ir nepieciešams, ja vien :other ir :values.',
    'required_with' => ' :attribute laukums ir nepieciešams, kad :values ir pieejama.',
    'required_with_all' => ' :attribute laukums ir nepieciešams, kad :values ir pieejama.',
    'required_without' => ' :attribute laukums ir nepieciešams, kad :values nav pieejama.',
    'required_without_all' => ' :attribute laukums ir nepieciešams, kad neviena no :values nav pieejama.',
    'same' => ' :attribute un :citiem ir jāsakrīt.',
    'size' => [
        'array' => ' :attribute jāsatur :size vienības.',
        'file' => ' :attribute jābūt :size kilobaiti.',
        'numeric' => ' :attribute jābūt :size.',
        'string' => ' :attribute jābūt :size zīmes.',
    ],
    'starts_with' => ':attribute jāsāk ar kādu no šīm :values.',
    'string' => ' :attribute jābūt virknē.',
    'timezone' => ' :attribute jābūt derīgā zonā.',
    'unique' => ' :attribute jau ir aizņemts.',
    'uploaded' => ' :attribute netika augšuplādēts.',
    'url' => ' :attribute formāts ir nederīgs.',
    'uuid' => ':attribute jābūt derīgam UUID.',

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
            'rule-name' => 'ziņa pēc pieprasījuma',
        ],
        'invalid_currency'      => ':attribute kods nav derīgs.',
        'invalid_amount'        => 'Daudzums :attribute ir nederīgs.',
        'invalid_extension'     => 'Faila paplašinājums nav derīgs.',
        'invalid_dimension'     => ':attribute izmēriem jābūt maksimāli :width x :height px.',
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
