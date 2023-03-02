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

    'accepted' => 'Полето :attribute мора да биде прифатено.',
    'accepted_if' => ':attribute мора да се прифати кога :other е :value.',
    'active_url' => 'Полето :attribute не е валиден URL.',
    'after' => 'Полето :attribute мора да биде датум после :date.',
    'after_or_equal' => ':attribute мора да биде датум после или на :date.',
    'alpha' => 'Полето :attribute може да содржи само букви.',
    'alpha_dash' => 'Полето :attribute може да содржи само букви, цифри, долна црта и тире.',
    'alpha_num' => 'Полето :attribute може да содржи само букви и цифри.',
    'array' => 'Полето :attribute мора да биде низа.',
    'before' => 'Полето :attribute мора да биде датум пред :date.',
    'before_or_equal' => ':attribute мора да биде датум пред или на :date.',
    'between' => [
        'array' => 'Полето :attribute мора да има помеѓу :min - :max карактери.',
        'file' => 'Полето :attribute мора да биде помеѓу :min и :max килобајти.',
        'numeric' => 'Полето :attribute мора да биде помеѓу :min и :max.',
        'string' => 'Полето :attribute мора да биде помеѓу :min и :max карактери.',
    ],
    'boolean' => 'The :attribute field must be true or false',
    'confirmed' => 'Полето :attribute не е потврдено.',
    'current_password' => 'Лозинката е погрешна.',
    'date' => 'Полето :attribute не е валиден датум.',
    'date_equals' => 'Полето :attribute мора да биде датум еднаков на  :date.',
    'date_format' => 'Полето :attribute не е во формат :format.',
    'declined' => ':attribute мора да биде одбиено.',
    'declined_if' => ':attribute мора да се одбие кога :other е :value.',
    'different' => 'Полињата :attribute и :other треба да се различни.',
    'digits' => 'Полето :attribute треба да има :digits цифри.',
    'digits_between' => 'Полето :attribute треба да има помеѓу :min и :max цифри.',
    'dimensions' => ':attribute има неважечки димензии на сликата.',
    'distinct' => 'Полето :attribute има дупликат вредност.',
    'doesnt_start_with' => ':attribute не може да започнува со една од следниве :values',
    'email' => 'Полето :attribute не е во валиден формат.',
    'ends_with' => 'Атрибутот :attribute мора да завршува со следниве вредности: :values',
    'enum' => 'Избраниот :attribute е невалиден.',
    'exists' => 'Избранато поле :attribute веќе постои.',
    'file' => ':attribute мора да биде датотека.',
    'filled' => 'Полето :attribute е задолжително.',
    'gt' => [
        'array' => ':attribute мора да има повеќе од :value ставки.',
        'file' => ':attribute мора да биде поголем од :value килобајти.',
        'numeric' => 'Полето :attribute мора да биде поголемо од :value.',
        'string' => ':attribute мора да биде поголем од :value знаци.',
    ],
    'gte' => [
        'array' => ':attribute мора да има  :value ставки или повеќе.',
        'file' => ':attribute мора да биде еднаков или поголем од :value килобајти.',
        'numeric' => ':attribute мора да биде еднаков или поголем од :value.',
        'string' => ' :attribute мора да биде еднаков или поголем од :value знаци.',
    ],
    'image' => 'Полето :attribute мора да биде слика.',
    'in' => 'Избраното поле :attribute е невалидно.',
    'in_array' => 'Полето :attribute не постои во :other.',
    'integer' => 'Полето :attribute мора да биде цел број.',
    'ip' => 'Полето :attribute мора да биде IP адреса.',
    'ipv4' => ':attribute мора да биде валидна IPv4 адреса.',
    'ipv6' => ':attribute мора да биде валидна IPv6 адреса.',
    'json' => ':attribute мора да биде валиден JSON стринг.',
    'lt' => [
        'array' => ':attribute мора да има помалку од :value ставки.',
        'file' => ':attribute мора да биде помал од :value килобајти.',
        'numeric' => ' :attribute мора да биде помало од :value.',
        'string' => ':attribute мора да биде помало од :value знаци.',
    ],
    'lte' => [
        'array' => ':attribute не смее да има повеќе од :value ставки.',
        'file' => ':attribute мора да биде еднаков или помал од :value килобајти.',
        'numeric' => ':attribute мора да биде еднаков или помал од :value.',
        'string' => ' :attribute мора да биде еднаков или помал од :value знаци.',
    ],
    'mac_address' => ' :attribute мора да биде валидна MAC адреса.',
    'max' => [
        'array' => 'Полето :attribute не може да има повеќе од :max карактери.',
        'file' => 'Полето :attribute мора да биде помало од :max килобајти.',
        'numeric' => 'Полето :attribute мора да биде помало од :max.',
        'string' => 'Полето :attribute мора да има помалку од :max карактери.',
    ],
    'mimes' => 'Полето :attribute мора да биде фајл од типот: :values.',
    'mimetypes' => 'Полето :attribute мора да биде фајл од типот: :values.',
    'min' => [
        'array' => 'Атрибутот :attribute мора да има минимум :min ставки.',
        'file' => 'Полето :attribute мора да биде минимум :min килобајти.',
        'numeric' => 'Полето :attribute мора да биде минимум :min.',
        'string' => 'Полето :attribute мора да има минимум :min карактери.',
    ],
    'multiple_of' => ':attribute мора да биде производ од :values.',
    'not_in' => 'Избраното поле :attribute е невалидно.',
    'not_regex' => 'Форматот на :attribute не е валиден.',
    'numeric' => 'Полето :attribute мора да биде број.',
    'password' => [
        'letters' => ':attribute мора да содржи најмалку една буква.',
        'mixed' => ':attribute мора да содржи најмалку една голема и една мала буква.',
        'numbers' => ':attribute мора да содржи најмалку една бројка.',
        'symbols' => ':attribute мора да содржи најмалку еден симбол.',
        'uncompromised' => 'Оваа :attribute се појави во истек на информации. Одберете друга :attribute.',
    ],
    'present' => 'Полето :attribute е задолжително.',
    'prohibited' => 'Полето :attribute е недозволено.',
    'prohibited_if' => 'Полето :attribute е недозволено кога :other е :value.',
    'prohibited_unless' => 'Полето :attribute е недозволено освен ако :other е во :values.',
    'prohibits' => 'Полето  :attribute не дозволува :other да бидат присутни.',
    'regex' => 'Полето :attribute е во невалиден формат.',
    'required' => 'Полето :attribute е задолжително.',
    'required_array_keys' => 'Полет  :attribute мора да содржи записи за: :values.',
    'required_if' => 'Полето :attribute е задолжително, кога :other е :value.',
    'required_unless' => 'Полето :attribute е задолжително, освен ако :other е :values.',
    'required_with' => 'Полето :attribute е задолжително, кога е внесено :values.',
    'required_with_all' => 'Полето :attribute е задолжително, кога е внесено :values.',
    'required_without' => 'Полето :attribute е задолжително, кога не е внесено :values.',
    'required_without_all' => 'Полето :attribute е задолжително кога не постои ниту една :values.',
    'same' => 'Полињата :attribute и :other треба да совпаѓаат.',
    'size' => [
        'array' => 'Атрибутот :attribute мора да има :size ставки.',
        'file' => 'Полето :attribute мора да биде :size килобајти.',
        'numeric' => 'Полето :attribute мора да биде :size.',
        'string' => 'Полето :attribute мора да има :size карактери.',
    ],
    'starts_with' => ':attribute мора  да започнува со една од следниве :values',
    'string' => ':attribute мора да биде стринг.',
    'timezone' => ':attribute мора да биде валидна зона.',
    'unique' => 'Полето :attribute веќе постои.',
    'uploaded' => ':attribute не е прикачен.',
    'url' => 'Полето :attribute не е во валиден формат.',
    'uuid' => ':attribute мора да биде валидно UUID',

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
            'rule-name' => 'Прилагодена порака',
        ],
        'invalid_currency'      => 'Полето :attribute не е во валиден формат.',
        'invalid_amount'        => 'Вредноста на :attribute не е валидна.',
        'invalid_extension'     => 'Екстензијата на датотеката не е валидна.',
        'invalid_dimension'     => 'Големината на  :attribute мора да е најмногу :width x :height пиксели.',
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
