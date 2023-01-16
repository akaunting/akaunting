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

    'accepted' => ':attribute muss akzeptiert werden.',
    'accepted_if' => ':attribute muss akzeptiert werden, wenn :other :value ist.',
    'active_url' => ':attribute ist keine valide URL.',
    'after' => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach oder gleich dem :date sein.',
    'alpha' => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash' => ':attribute darf nur aus Buchstaben, Zahlen und Gedankenstrichen bestehen.',
    'alpha_num' => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder gleich dem :date sein.',
    'between' => [
        'array' => ':attribute soll mindestens :min und darf maximal :max Stellen haben.',
        'file' => ':attribute darf nur zwischen :min und :max kilobytes groß sein.',
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'string' => ':attribute muss mindestens :min und darf maximal :max Zeichen enthalten.',
    ],
    'boolean' => ':attribute muss Wahr oder Falsch sein.',
    'confirmed' => ':attribute Bestätigung stimmt nicht überein.',
    'current_password' => 'Das Passwort ist falsch.',
    'date' => ':attribute ist kein gültiges Datum.',
    'date_equals' => ':attribute muss ein Datum gleich :date sein.',
    'date_format' => ':attribute passt nicht zur :format Formatierung.',
    'declined' => ':attribute muss abgelehnt werden.',
    'declined_if' => ':attribute muss abgelehnt werden, wenn :other :value ist.',
    'different' => ':attribute und :other müssen sich <strong>unterscheiden</strong>.',
    'digits' => ':attribute muss :digits Stellen haben.',
    'digits_between' => ':attribute soll mindestens :min und darf maximal :max Stellen haben.',
    'dimensions' => ':attribute hat ungültige Bildabmessungen.',
    'distinct' => ':attribute hat einen doppelten Wert.',
    'doesnt_start_with' => ':attribute darf nicht mit einem der folgenden Werte beginnen: :values',
    'email' => ':attribute Attribut muss eine gültige <strong>E-Mail-Adresse</strong> sein.',
    'ends_with' => ':attribute muss mit einem der folgenden Zeichen enden: :values',
    'enum' => 'Das ausgewählte :attribute ist ungültig.',
    'exists' => 'Das ausgewählte :attribute ist ungültig.',
    'file' => ':attribute muss eine <strong>Datei</strong> sein.',
    'filled' => ':attribute Feld muss einen <strong>Wert</strong> haben.',
    'gt' => [
        'array' => ':attribute muss mehr als :value Elemente haben.',
        'file' => ':attribute muss größer als :value Kilobytes sein.',
        'numeric' => ':attribute muss größer als :value sein.',
        'string' => ':attribute muss länger als :value Zeichen sein.',
    ],
    'gte' => [
        'array' => ':attribute muss mindestens :value Elemente haben.',
        'file' => ':attribute muss größer oder gleich :value Kilobytes sein.',
        'numeric' => ':attribute muss größer oder gleich :value sein.',
        'string' => ':attribute muss mindestens :value Zeichen lang sein.',
    ],
    'image' => ':attribute muss ein <strong>Bild</strong> sein.',
    'in' => 'Das ausgewählte :attribute ist ungültig.',
    'in_array' => ':attribute Feld existiert nicht in :other.',
    'integer' => ':attribute muss eine <strong>Ganzzahl</strong> sein.',
    'ip' => ':attribute muss eine gültige IP Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4 Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6 Adresse sein.',
    'json' => ':attribute muss eine gültiger JSON-String sein.',
    'lt' => [
        'array' => ':attribute muss weniger als :value Elemente haben.',
        'file' => ':attribute muss kleiner als :value Kilobytes sein.',
        'numeric' => ':attribute muss kleiner als :value sein.',
        'string' => ':attribute muss kürzer als :value Zeichen sein.',
    ],
    'lte' => [
        'array' => ':attribute darf nicht mehr als :value-Elemente haben.',
        'file' => ':attribute muss kleiner oder gleich :value Kilobytes sein.',
        'numeric' => ':attribute muss kleiner oder gleich :value sein.',
        'string' => ':attribute muss kleiner oder gleich :value Zeichen sein.
',
    ],
    'mac_address' => ':attribute muss eine gültige MAC-Adresse sein.',
    'max' => [
        'array' => ':attribute darf nicht mehr als :max Werte haben.',
        'file' => ':attribute darf nicht größer als :max Kilobyte sein.',
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'string' => ':attribute darf nicht größer als :max Zeichen sein.',
    ],
    'mimes' => ':attribute muss eine Datei des Typs :values sein.',
    'mimetypes' => ':attribute muss eine Datei des Typs :values sein.',
    'min' => [
        'array' => ':attribute muss mindestens :min Artikel haben.',
        'file' => ':attribute muss mindestens :min Kilobyte groß sein.',
        'numeric' => ':attribute muss mindestens :min sein.',
        'string' => ':attribute benötigt mindestens :min Zeichen.',
    ],
    'multiple_of' => ':attribute muss ein Vielfaches von :value sein.',
    'not_in' => 'Das ausgewählte :attribute ist ungültig.',
    'not_regex' => ':attribute Format ist ungültig.',
    'numeric' => ':attribute muss eine Zahl sein.',
    'password' => [
        'letters' => 'Das :attribute muss mindestens einen Buchstaben enthalten.',
        'mixed' => 'Das :attribute muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten.',
        'numbers' => 'Das :attribute muss mindestens eine Ziffer enthalten.',
        'symbols' => 'Das :attribute muss mindestens ein Sonderzeichen enthalten.',
        'uncompromised' => 'Das eingegebene :attribute wurde in einem Datenleck gefunden. Bitte wählen Sie ein anderes :attribute.',
    ],
    'present' => ':attribute Feld muss <strong>vorhanden sein</strong>.',
    'prohibited' => 'Das Feld :attribute ist nicht erlaubt.',
    'prohibited_if' => 'Das Feld :attribute ist verboten, wenn :other :value ist.',
    'prohibited_unless' => 'Das Feld :attribute ist verboten, es sei denn, :other ist in :values ​​enthalten.',
    'prohibits' => 'Das Feld :attribute verbietet das Vorhandensein von :other.',
    'regex' => ':attribute Format ist <strong>ungültig</strong>.',
    'required' => ':attribute Feld ist <strong>erforderlich</strong>.',
    'required_array_keys' => ':attribute muss folgende Einträge enthalten: :values',
    'required_if' => ':attribute wird benötigt wenn :other :value entspricht.',
    'required_unless' => ':attribute wird benötigt es sei denn :other ist in :values.',
    'required_with' => ':attribute wird benötigt wenn :values vorhanden ist.',
    'required_with_all' => ':attribute wird benötigt wenn :values vorhanden ist.',
    'required_without' => ':attribute wird benötigt wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute wird benötigt wenn keine :values vorhanden sind.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'array' => ':attribute muss :size Artikel enthalten.',
        'file' => ':attribute muss :size Kilobyte groß sein.',
        'numeric' => ':attribute muss :size groß sein.',
        'string' => ':attribute muss <strong>:size Zeichen</strong> haben.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Werte beginnen: :values.',
    'string' => ':attribute muss ein <strong>String</strong> sein.',
    'timezone' => ':attribute muss eine valide Zeitzone sein.',
    'unique' => ':attribute wurde bereits <strong>vergeben</strong>.',
    'uploaded' => 'Das :attribute konnte <strong>nicht hochgeladen</strong> werden.',
    'url' => ':attribute Format ist <strong>ungültig</strong>.',
    'uuid' => ':attribute muss ein UUID sein.',

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
        'invalid_currency'      => 'Das :attribute Kürzel ist ungültig.',
        'invalid_amount'        => 'Die Menge von :attribute ist ungültig.',
        'invalid_extension'     => 'Die Dateiendung ist ungültig.',
        'invalid_dimension'     => ':attribute Dimensionen dürfen maximal :width x :height px sein.',
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
