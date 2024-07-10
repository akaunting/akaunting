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

    'accepted' => ':attribute doit être accepté.',
    'accepted_if' => 'Le champ :attribute doit être accepté lorsque :other est :value.',
    'active_url' => ':attribute n\'est pas une URL valide.',
    'after' => ':attribute doit être une date après :date.',
    'after_or_equal' => ':attribute doit être une date après ou égale à :date.',
    'alpha' => ':attribute ne peut contenir que des lettres.',
    'alpha_dash' => ':attribute ne peut contenir que des lettres, des nombres, et des tirets.',
    'alpha_num' => ':attribute ne peut contenir que des caractères alphanumériques.',
    'array' => ':attribute doit être un tableau.',
    'before' => ':attribute doit être une date avant :date.',
    'before_or_equal' => ':attribute doit être une date après ou égale à :date.',
    'between' => [
        'array' => ':attribute doit contenir entre :min et :max chiffres.',
        'file' => ':attribute doit être entre :min et :max kilo-octets.',
        'numeric' => ':attribute doit être entre :min et :max.',
        'string' => ':attribute doit contenir entre :min et :max caractères.',
    ],
    'boolean' => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed' => 'La confirmation du ":attribute" ne concordent pas.',
    'current_password' => 'Mot de passe incorrect.',
    'date' => ':attribute n\'est pas une date valide.',
    'date_equals' => 'Le champ :attribute doit être une date égale à :date.',
    'date_format' => ':attribute ne respecte pas le format :format.',
    'declined' => 'Le champ :attribute doit être refusé.',
    'declined_if' => 'Le champ :attribute doit être refusé lorsque :other est :value.',
    'different' => 'Les champs :attribute et :other doivent être différents.',
    'digits' => ':attribute doit contenir :digits chiffres.',
    'digits_between' => ':attribute doit contenir entre :min et :max chiffres.',
    'dimensions' => ':attribut possède des dimensions d\'image non valide.',
    'distinct' => ':champ a une valeur dupliquée.',
    'doesnt_start_with' => 'Le champ :attribute doit commencer par l\'une des valeurs suivantes : :values.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'ends_with' => 'Le champ :attribute doit se terminer par une des valeurs suivantes : :values',
    'enum' => 'Le champ :attribute sélectionné est invalide.',
    'exists' => ':attribute selectionné est invalide.',
    'file' => 'Le champ :attribute doit être un fichier.',
    'filled' => 'Le champ :attribute doit avoir une valeur.',
    'gt' => [
        'array' => 'Le champ :attribute doit avoir plus de :value éléments.',
        'file' => 'La taille du fichier de :attribute doit être supérieure à :value kilo-octets.',
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'string' => 'Le texte :attribute doit contenir plus de :value caractères.',
    ],
    'gte' => [
        'array' => 'Le tableau :attribute doit contenir au moins :value éléments.',
        'file' => 'La taille du fichier de :attribute doit être supérieure ou égale à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être supérieure ou égale à :value.',
        'string' => 'Le champ :attribute doit être supérieur ou égal à :value caractères.',
    ],
    'image' => 'Le champ :attribute doit être une image.',
    'in' => ':attribute est invalide.',
    'in_array' => 'Le champ :attribute n\'existe pas dans :other.',
    'integer' => 'Le champ :attribute doit être un entier.',
    'ip' => ':attribute doit être une adresse IP valide.',
    'ipv4' => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6' => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json' => ':attribute doit respecté le format JSON.',
    'lt' => [
        'array' => 'Le champ :attribute doit avoir moins de :value éléments.',
        'file' => ':attribute doit être inférieur à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être inférieure à :value.',
        'string' => 'Le champ :attribute doit être inférieur à :value caractères.',
    ],
    'lte' => [
        'array' => 'Le champ :attribute ne doit pas avoir plus de :value éléments.',
        'file' => ':attribute doit être inférieur ou égal à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être inférieure ou égale à :value.',
        'string' => 'Le texte :attribute doit contenir au plus :value caractères.',
    ],
    'mac_address' => 'Le champ :attribute doit être une adresse MAC valide.',
    'max' => [
        'array' => ':attribute ne doit pas dépasser :max marchandises.',
        'file' => ':attribute ne doit pas dépasser :max kilo-octets.',
        'numeric' => ':attribute ne peut pas être plus grand que :max.',
        'string' => ':attribute ne doit pas faire plus de :max caractères.',
    ],
    'mimes' => 'Le fichier :attribute doit être de type: :values.',
    'mimetypes' => 'Le fichier :attribute doit être de type: :values.',
    'min' => [
        'array' => ':attribute doit avoir au moins :min marchandises.',
        'file' => ':attribute doit faire au moins :min kilo-octets.',
        'numeric' => ':attribute doit être au moins :min.',
        'string' => ':attribute doit faire au moins :min caractères.',
    ],
    'multiple_of' => 'Le champ :attribute doit être un multiple de :value',
    'not_in' => ':attribute est invalide.',
    'not_regex' => 'Le format du champ :attribute est invalide.',
    'numeric' => ':attribute doit être un nombre.',
    'password' => [
        'letters' => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed' => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers' => 'Le champ :attribute doit contenir au moins un nombre.',
        'symbols' => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Le champ :attribute est apparu dans une fuite de données. Veuillez choisir un autre :attribute.',
    ],
    'present' => 'Le champ :attribute doit être présent.',
    'prohibited' => 'Le champ :attribute est interdit.',
    'prohibited_if' => 'Le champ :attribute est interdit lorsque :other est :value.',
    'prohibited_unless' => 'Le champ :attribute est interdit sauf si :other est dans :values.',
    'prohibits' => 'L\'attribut :attribute est interdit :other d\'être présent.',
    'regex' => 'Le format du champ :attribute est invalide.',
    'required' => 'Le champ :attribute est obligatoire.',
    'required_array_keys' => 'Le champ :attribute doit contenir des entrées pour: :values.',
    'required_if' => 'Le champ :attribute est nécessaire quand :other vaut :value.',
    'required_unless' => 'Le champ :attribute est nécessaire sauf si :other se trouve dans :values.',
    'required_with' => 'Le champ :attribute est nécessaire quand :values est présent.',
    'required_with_all' => 'Le champ :attribute est nécessaire quand :values est présent.',
    'required_without' => 'Le champ :attribute est nécessaire quand :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est nécessaire quand aucun des :values sont présent.',
    'same' => ':attribute et :other doivent correspondre.',
    'size' => [
        'array' => ':attribut doit contenir :size marchandises.',
        'file' => ':attribute doit faire :size kilo-octets.',
        'numeric' => ':attribute doit faire :size.',
        'string' => 'Le texte de :attribute doit contenir :size caractères.',
    ],
    'starts_with' => 'L\'attribut :attribute doit commencer par l\'une des valeurs suivantes : :values.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone' => ':attribute doit être une zone valide.',
    'unique' => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded' => 'Le fichier du champ :attribute n\'a pu être envoyé.',
    'url' => 'Le format de l\'URL de :attribute n\'est pas valide.',
    'uuid' => 'Le champ :attribute doit être un identifiant valide',

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
        'invalid_currency'      => 'Le code de :attribute est invalide.',
        'invalid_amount'        => 'Le montant :amount n\'est pas valide.',
        'invalid_extension'     => 'L\'extension de fichier n\'est pas valide.',
        'invalid_dimension'     => 'Les dimensions de :attribute doivent être max :width x :height px.',
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
