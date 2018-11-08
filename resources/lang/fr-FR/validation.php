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

    'accepted'             => ':attribute doit être accepté.',
    'active_url'           => ':attribute n\'est pas une URL valide.',
    'after'                => ':attribute doit être une date après :date.',
    'after_or_equal'       => ':attribute doit être une date après ou égale à :date.',
    'alpha'                => ':attribute ne peut contenir que des lettres.',
    'alpha_dash'           => ':attribute ne peut contenir que des lettres, des nombres, et des tirets.',
    'alpha_num'            => ':attribute ne peut contenir que des caractères alphanumériques.',
    'array'                => ':attribute doit être un tableau.',
    'before'               => ':attribute doit être une date avant :date.',
    'before_or_equal'      => ':attribute doit être une date après ou égale à :date.',
    'between'              => [
        'numeric' => ':attribute doit être entre :min et :max.',
        'file'    => ':attribute doit être entre :min et :max kilo-octets.',
        'string'  => ':attribute doit contenir entre :min et :max caractères.',
        'array'   => ':attribute doit contenir entre :min et :max chiffres.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du ":attribute" ne concordent pas.',
    'date'                 => ':attribute n\'est pas une date valide.',
    'date_format'          => ':attribute ne respecte pas le format :format.',
    'different'            => ':attribute et :other doivent être différents.',
    'digits'               => ':attribute doit contenir :digits chiffres.',
    'digits_between'       => ':attribute doit contenir entre :min et :max chiffres.',
    'dimensions'           => ':attribut possède des dimensions d\'image non valide.',
    'distinct'             => ':champ a une valeur dupliquée.',
    'email'                => ':attribute doit être une adresse email valide.',
    'exists'               => ':attribute selectionné est invalide.',
    'file'                 => ':attribut doit être un fichier.',
    'filled'               => ':champ d’attribut doit avoir une valeur.',
    'image'                => ':attribute doit être une image.',
    'in'                   => ':attribute est invalide.',
    'in_array'             => 'Le champ :attribute n’existe pas dans :other.',
    'integer'              => ':attribute doit être un nombre entier.',
    'ip'                   => ':attribute doit être une adresse IP valide.',
    'json'                 => ':attribute doit respecté le format JSON.',
    'max'                  => [
        'numeric' => ':attribute ne peut pas être plus grand que :max.',
        'file'    => ':attribute ne doit pas dépasser :max kilo-octets.',
        'string'  => ':attribute ne doit pas faire plus de :max caractères.',
        'array'   => ':attribute ne doit pas dépasser :max marchandises.',
    ],
    'mimes'                => 'Le fichier :attribute doit être de type: :values.',
    'mimetypes'            => 'Le fichier :attribute doit être de type: :values.',
    'min'                  => [
        'numeric' => ':attribute doit être au moins :min.',
        'file'    => ':attribute doit faire au moins :min kilo-octets.',
        'string'  => ':attribute doit faire au moins :min caractères.',
        'array'   => ':attribute doit avoir au moins :min marchandises.',
    ],
    'not_in'               => ':attribute est invalide.',
    'numeric'              => ':attribute doit être un nombre.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format de :attribute est invalide.',
    'required'             => 'Le champ :attribute est nécessaire.',
    'required_if'          => 'Le champ :attribute est nécessaire quand :other vaut :value.',
    'required_unless'      => 'Le champ :attribute est nécessaire sauf si :other se trouve dans :values.',
    'required_with'        => 'Le champ :attribute est nécessaire quand :values est présent.',
    'required_with_all'    => 'Le champ :attribute est nécessaire quand :values est présent.',
    'required_without'     => 'Le champ :attribute est nécessaire quand :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est nécessaire quand aucun des :values sont présent.',
    'same'                 => ':attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => ':attribute doit faire :size.',
        'file'    => ':attribute doit faire :size kilo-octets.',
        'string'  => ':attribute doit faire :size caractères.',
        'array'   => ':attribut doit contenir :size marchandises.',
    ],
    'string'               => ':attribute doit être une chaîne de caractères.',
    'timezone'             => ':attribute doit être une zone valide.',
    'unique'               => ':attribute est déjà pris.',
    'uploaded'             => ':attribut n’a pas pu être envoyer.',
    'url'                  => 'Le format de :attribute est invalide.',

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
            'rule-name' => 'Un message spécifique sera affiché si le paramètre \'Utiliser message spécifique\' est implémenté pour le champ \'Message hors-ligne\'',
        ],
        'invalid_currency' => 'Le code de :attribute est invalide.',
        'invalid_amount'   => 'Le montant :amount n\'est pas valide.',
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
