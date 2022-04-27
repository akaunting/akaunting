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
        'numeric' => ' :attribute jābūt starp :min un :max.',
        'file' => ' :attribute jābūt starp :min un :max kilobaiti.',
        'string' => ' :attribute jābūt no :min līdz :max zīmēm.',
        'array' => ' :attribute jābūt no :min līdz :max vienībām.',
    ],
    'boolean' => ' :attribute laiciņam jābūt atbilstošam vai neatbilstošam.',
    'confirmed' => ' :attribute apstiprinājums neatbilst.',
    'current_password' => 'Jūsu parole nav pareiza.',
    'date' => ' :attribute nav derīgs.',
    'date_equals' => ' :attribute ir jābūt ar datumu kas vienāds :datums.',
    'date_format' => ' :attribute neatbilst formātam :format.',
    'different' => ' :attribute un :other ir jābūt citiem.',
    'digits' => ' :attribute ir jābūt :digits ciparam.',
    'digits_between' => ' :attribute ir jābūt :min un :max ciparam.',
    'dimensions' => ' :attribute ir nederīgs attēla izmērs.',
    'distinct' => ' :attribute laikam ir dubulta vērtība.',
    'email' => ' :attribute derīgam e-pastam.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'file' => ' :attribute jābūt failam.',
    'filled' => ':attribute lauks ir nepieciešams.',
    'gt' => [
        'numeric' => ' :attribute jābūt lielākam par :value.',
        'file' => ' :attribute jābūt lielākam par :max kilobaiti.',
        'string' => ' :attribute jābūt lielākam par :value rakstzīmēm.',
        'array' => ' :attribute jābūt lielākai kā :value vienībai.',
    ],
    'gte' => [
        'numeric' => ' :attribute jābūt lielākam vai vienādam ar :value.',
        'file' => ' :attribute jābūt lielākai vai vienādai ar :max kilobaitiem.',
        'string' => ' :attribute jābūt lielākam vai vienādam ar :value rakstzīmēm.',
        'array' => ':attribute jābūt :value vienumi vai vairāk.',
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
        'numeric' => ':attribute jābūt mazākam par :value.',
        'file' => 'The :attribute jābūt mazākam par :value kilobaitiem.',
        'string' => ' :attribute jābūt mazākam par :value rakstzīmēm.',
        'array' => 'The :attribute jābūt mazākam par :value vienībām.',
    ],
    'lte' => [
        'numeric' => ' :attribute jābūt mazākam vai vienādam ar :value.',
        'file' => ' :attribute jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'string' => ' :attribute jābūt mazākam vai vienādam ar :value rakstzīmēm.',
        'array' => 'The :attribute nedrīkst būt vairāk kā :value vienībām.',
    ],
    'max' => [
        'numeric' => ' :attribute nedrīkst pārsniegt :max.',
        'file' => ' :attribute nedrīkst pārsniegt :max kilobaiti.',
        'string' => ' :attribute nedrīkst pārsniegt :max zīmes.',
        'array' => ' :attribute nedrīkst pārsniegt :max vienības.',
    ],
    'mimes' => ' :attribute jābūt faila tipam: :values',
    'mimetypes' => ' :attribute jābūt faile tipam: :values.',
    'min' => [
        'numeric' => ' :attribute jābūt vismaz :min.',
        'file' => ' :attribute jābūt vismaz :min kilobaiti.',
        'string' => ' :attribute jābūt vismaz :min zīmes.',
        'array' => ' :attribute jāsatur vismaz :min vienības.',
    ],
    'multiple_of' => ':attribute jābūt skaitlim :value.',
    'not_in' => ' izvēlieties :attribute ir nederīgs.',
    'not_regex' => ' :attribute formāts ir nederīgs.',
    'numeric' => ' :attribute jābūt skaitlim.',
    'password' => 'Jūsu parole nav pareiza.',
    'present' => ' :attribute laikums ir nepieciešams.',
    'regex' => ' :attribute formāts ir nederīgs.',
    'required' => ' :attribute laukums ir nepieciešams.',
    'required_if' => ' :attribute laukums ir nepieciešams, ja vien :other ir :values.',
    'required_unless' => ' :attribute laukums ir nepieciešams, ja vien :other ir :values.',
    'required_with' => ' :attribute laukums ir nepieciešams, kad :values ir pieejama.',
    'required_with_all' => ' :attribute laukums ir nepieciešams, kad :values ir pieejama.',
    'required_without' => ' :attribute laukums ir nepieciešams, kad :values nav pieejama.',
    'required_without_all' => ' :attribute laukums ir nepieciešams, kad neviena no :values nav pieejama.',
    'prohibited' => ':attribute lauks ir aizliegts.',
    'prohibited_if' => ':attribute lauks ir aizliegts, ja :other ir :value.',
    'prohibited_unless' => ' :attribute lauks ir aizliegts, ja :other ir :values.',
    'same' => ' :attribute un :citiem ir jāsakrīt.',
    'size' => [
        'numeric' => ' :attribute jābūt :size.',
        'file' => ' :attribute jābūt :size kilobaiti.',
        'string' => ' :attribute jābūt :size zīmes.',
        'array' => ' :attribute jāsatur :size vienības.',
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
