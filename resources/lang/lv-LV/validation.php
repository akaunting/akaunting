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

    'accepted'             => ':attribute nepieciešams akceptēt.',
    'active_url'           => ':attribute nav derīga URL adrese.',
    'after'                => ':attribute datumam jābūt pēc :date datuma.',
    'after_or_equal'       => ':attribute datumam jābūt vienādam vai vēlākam par :date.',
    'alpha'                => ':attribute jāsatur tikai burti.',
    'alpha_dash'           => ':attribute var saturēt tikai burtus, ciparus un domuzīmes.',
    'alpha_num'            => ':attribute var saturēt tikai burtus un ciparus.',
    'array'                => ':attribute nepieciešams būt datu masīvam.',
    'before'               => ':attribute jābūt pirms :date.',
    'before_or_equal'      => ':attribute jābūt vienādam vai pirms :date.',
    'between'              => [
        'numeric' => ':attribute nepieciešams būt starp :min un :max.',
        'file'    => ':attribute nepieciešams būt starp :min un :max kilobaitiem.',
        'string'  => ':attribute nepieciešams būt starp :min un :max zīmēm.',
        'array'   => ':attribute nepieciešams būt starp :min un :max vienībām.',
    ],
    'boolean'              => ':attribute jābūt true vai false.',
    'confirmed'            => ':attribute apstiprinājums nav derīgs.',
    'date'                 => ':attribute nav derīgs datums.',
    'date_format'          => ':attribute neatbilst formātam :format.',
    'different'            => ':attribute un :other jābūt atšķirīgiem.',
    'digits'               => ':attribute jābūt :digits zīmju skaitlim.',
    'digits_between'       => ':attribute vajag būt starp :min un :max zīmēm.',
    'dimensions'           => ':attribute nav derīgs attēla izmērs.',
    'distinct'             => ':attribute laukam vērtība atkārtojas.',
    'email'                => ':attribute jābūt derīgai e-pasta adresei',
    'exists'               => 'Izvēlētā vērtība :attribute nav derīga.',
    'file'                 => ':attribute jābūt failam.',
    'filled'               => ':attribute jābūt norādītai vērtībai.',
    'image'                => ':attribute jābūt attēlam.',
    'in'                   => 'Atzīmētā vērtība :attribute nav derīga.',
    'in_array'             => ':attribute vērtība neeksistē :other.',
    'integer'              => ':attribute jābūt veselam skaitlim.',
    'ip'                   => ':attribute jābūt derīgai IP adresei.',
    'json'                 => ':attribute jābūt derīgai JSON vērtībai.',
    'max'                  => [
        'numeric' => ':attribute nevar būt lielāks par :max.',
        'file'    => ':attribute nevar būt lielāks par :max kilobaitiem.',
        'string'  => ':attribute nevar būt garāks par :max zīmēm.',
        'array'   => ':attribute nevar saturēt vairāk kā :max vērtības.',
    ],
    'mimes'                => ':attribute ir jābūt šāda tipa failam: :values.',
    'mimetypes'            => ':attribute ir jābūt šāda tipa failam: :values.',
    'min'                  => [
        'numeric' => ':attribute jābūt vismaz :min.',
        'file'    => ':attribute jābūt lielākam par :min kilobaitiem.',
        'string'  => ':attribute jābūt vismaz zīmes :min garam.',
        'array'   => ':attribute jāsatur vismaz :min vērtības.',
    ],
    'not_in'               => 'Atzīmētā vērtība :attribute nav derīga.',
    'numeric'              => ':attribute jābūt skaitlim.',
    'present'              => ':attribute jābūt norādītam.',
    'regex'                => ':attribute formāts nav derīgs.',
    'required'             => ':attribute lauks ir obligāts.',
    'required_if'          => ':attribute jauks ir obligāts, ja :other ir :value.',
    'required_unless'      => ':attribute lauks ir obligāts, ja :other satur :values.',
    'required_with'        => ':attribute lauks ir obligāts, ja :values ir aizpildīta.',
    'required_with_all'    => ':attribute lauks ir obligāts, ja :values ir aizpildītas.',
    'required_without'     => ':attribute laukam jābūt aizpildītam, ja lauks :values nav aizpildīts.',
    'required_without_all' => ':attribute lauks ir jāaizpilda, ja lauki :values nav aizpildīti.',
    'same'                 => ':attribute un :other jābūt vienādiem.',
    'size'                 => [
        'numeric' => ':attribute jābūt :size.',
        'file'    => ':attribute jābūt :size kilobaiti.',
        'string'  => ':attribute jābūt :size zīmes.',
        'array'   => ':attribute jāsatur :size vērtības.',
    ],
    'string'               => ':attribute jābūt teksta vērtībai.',
    'timezone'             => ':attribute jābūt derīgai laika zonai.',
    'unique'               => ':attribute jau ir aizņemts.',
    'uploaded'             => ':attribute neizdevās augšupielādēt.',
    'url'                  => ':attribute formāts nav derīgs.',

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
        'invalid_currency' => ':attribute kods nav derīgs.',
        'invalid_amount'   => 'The amount :attribute is invalid.',
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
