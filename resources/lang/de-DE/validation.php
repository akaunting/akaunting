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

    'accepted'             => ':attribute muss akzeptiert werden.',
    'active_url'           => ':attribute ist keine valide URL.',
    'after'                => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'       => ':attribute muss ein Datum nach oder gleich dem :date sein.',
    'alpha'                => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash'           => ':attribute darf nur aus Buchstaben, Zahlen und Gedankenstrichen bestehen.',
    'alpha_num'            => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array'                => ':attribute muss ein Array sein.',
    'before'               => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal'      => ':attribute muss ein Datum vor oder gleich dem :date sein.',
    'between'              => [
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'file'    => ':attribute darf nur zwischen :min und :max kilobytes groß sein.',
        'string'  => ':attribute muss mindestens :min und darf maximal :max Zeichen enthalten.',
        'array'   => ':attribute soll mindestens :min und darf maximal :max Stellen haben.',
    ],
    'boolean'              => ':attribute muss Wahr oder Falsch sein.',
    'confirmed'            => ':attribute Bestätigung stimmt nicht überein.',
    'date'                 => ':attribute ist kein gültiges Datum.',
    'date_format'          => ':attribute passt nicht zur :format Formatierung.',
    'different'            => ':attribute und :other müssen sich unterscheiden.',
    'digits'               => ':attribute muss :digits Stellen haben.',
    'digits_between'       => ':attribute soll mindestens :min und darf maximal :max Stellen haben.',
    'dimensions'           => ':attribute hat ungültige Bildabmessungen.',
    'distinct'             => ':attribute hat einen doppelten Wert.',
    'email'                => ':attribute Attribut muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Das ausgewählte :attribute ist ungültig.',
    'file'                 => ':attribute muss eine Datei sein.',
    'filled'               => ':attribute muss einen Wert besitzen.',
    'image'                => ':attribute muss ein Bild sein.',
    'in'                   => 'Das ausgewählte :attribute ist ungültig.',
    'in_array'             => ':attribute Feld existiert nicht in :other.',
    'integer'              => ':attribute muss ein Integer-Wert (Ganz-Zahl) sein.',
    'ip'                   => ':attribute muss eine gültige IP Adresse sein.',
    'json'                 => ':attribute muss eine gültiger JSON-String sein.',
    'max'                  => [
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'file'    => ':attribute darf nicht größer als :max Kilobyte sein.',
        'string'  => ':attribute darf nicht größer als :max Zeichen sein.',
        'array'   => ':attribute darf nicht mehr als :max Werte haben.',
    ],
    'mimes'                => ':attribute muss eine Datei des Typs :values sein.',
    'mimetypes'            => ':attribute muss eine Datei des Typs :values sein.',
    'min'                  => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file'    => ':attribute muss mindestens :min Kilobyte groß sein.',
        'string'  => ':attribute benötigt mindestens :min Zeichen.',
        'array'   => ':attribute muss mindestens :min Artikel haben.',
    ],
    'not_in'               => 'Das ausgewählte :attribute ist ungültig.',
    'numeric'              => ':attribute muss eine Zahl sein.',
    'present'              => ':attribute Feld muss vorhanden sein.',
    'regex'                => ':attribute Format ist ungültig.',
    'required'             => ':attribute wird benötigt.',
    'required_if'          => ':attribute wird benötigt wenn :other :value entspricht.',
    'required_unless'      => ':attribute wird benötigt es sei denn :other ist in :values.',
    'required_with'        => ':attribute wird benötigt wenn :values vorhanden ist.',
    'required_with_all'    => ':attribute wird benötigt wenn :values vorhanden ist.',
    'required_without'     => ':attribute wird benötigt wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute wird benötigt wenn keine :values vorhanden sind.',
    'same'                 => ':attribute und :other müssen übereinstimmen.',
    'size'                 => [
        'numeric' => ':attribute muss :size groß sein.',
        'file'    => ':attribute muss :size Kilobyte groß sein.',
        'string'  => ':attribute muss :size Zeichen haben.',
        'array'   => ':attribute muss :size Artikel enthalten.',
    ],
    'string'               => ':attribute muss ein String sein.',
    'timezone'             => ':attribute muss eine valide Zeitzone sein.',
    'unique'               => ':attribute wird schon benutzt.',
    'uploaded'             => ':attribute Fehler beim Hochladen.',
    'url'                  => ':attribute Format ist ungültig.',

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
            'rule-name' => 'eigene Nachricht',
        ],
        'invalid_currency' => 'Das :attribute Kürzel ist ungültig.',
        'invalid_amount'   => 'Die Menge von :attribute ist ungültig.',
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
