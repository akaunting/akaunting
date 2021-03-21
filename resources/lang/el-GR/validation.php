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

    'accepted'             => 'Το πεδίο :attribute πρέπει να γίνει αποδεκτό.',
    'active_url'           => 'Το πεδίο :attribute δεν είναι αποδεκτή διεύθυνση URL.',
    'after'                => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία μετά από :date.',
    'after_or_equal'       => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία ίδια ή μετά από :date.',
    'alpha'                => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash'           => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς, και παύλες.',
    'alpha_num'            => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array'                => 'Το πεδίο :attribute πρέπει να είναι ένας πίνακας.',
    'before'               => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία πριν από :date.',
    'before_or_equal'      => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία ίδια ή πριν από :date.',
    'between'              => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max.',
        'file'    => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max kilobytes.',
        'string'  => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max χαρακτήρες.',
        'array'   => 'Το πεδίο :attribute πρέπει να έχει μεταξύ :min - :max αντικείμενα.',
    ],
    'boolean'              => 'Το πεδίο :attribute πρέπει να είναι true ή false.',
    'confirmed'            => 'Η επιβεβαίωση του :attribute δεν ταιριάζει.',
    'date'                 => 'Το πεδίο :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_format'          => 'Το πεδίο :attribute δεν είναι της μορφής :format.',
    'different'            => 'Το πεδίο :attribute και :other πρέπει να είναι <strong>διαφορετικά</strong>.',
    'digits'               => 'Το πεδίο :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between'       => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max ψηφία.',
    'dimensions'           => 'Το πεδίο :attribute περιέχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct'             => 'Το πεδίο :attribute περιέχει δύο φορές την ίδια τιμή.',
    'email'                => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη <strong>διεύθυνση email</strong>.',
    'ends_with'            => 'Το :attribute πρέπει να τελειώνει με ένα από τα παρακάτω: :values',
    'exists'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'file'                 => 'Το πεδίο :attribute πρέπει να είναι ένα <strong>αρχείο</strong>.',
    'filled'               => 'Το πεδίο :attribute πρέπει έχει μία <strong>αξία</strong>.',
    'image'                => 'Το πεδίο :attribute πρέπει να είναι <strong>image</strong>.',
    'in'                   => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'in_array'             => 'Το πεδίο :attribute δεν υπάρχει σε :other.',
    'integer'              => 'Το πεδίο :attribute πρέπει να είναι <strong>ακαίρεο</strong>.',
    'ip'                   => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη διεύθυνση IP.',
    'json'                 => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη συμβολοσειρά JSON.',
    'max'                  => [
        'numeric' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'file'    => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερό :max kilobytes.',
        'string'  => 'Το πεδίο :attribute δεν μπορεί να έχει περισσότερους από :max χαρακτήρες.',
        'array'   => 'Το πεδίο :attribute δεν μπορεί να έχει περισσότερα από :max αντικείμενα.',
    ],
    'mimes'                => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'mimetypes'            => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min'                  => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min.',
        'file'    => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string'  => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min χαρακτήρες.',
        'array'   => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
    ],
    'not_in'               => 'Το επιλεγμένο :attribute δεν είναι αποδεκτό.',
    'numeric'              => 'Το πεδίο :attribute πρέπει να είναι αριθμός.',
    'present'              => 'Το πεδίο :attribute πρέπει να <strong>υπάρχει</strong>.',
    'regex'                => 'Η μορφή του :attribute είναι <strong>μή έγκυρη</strong>.',
    'required'             => 'Το πεδίο :attribute <strong>απαιτείται</strong>.',
    'required_if'          => 'Το πεδίο :attribute είναι απαραίτητο όταν το πεδίο :other είναι :value.',
    'required_unless'      => 'Το πεδίο :attribute είναι απαραίτητο εκτός αν το πεδίο :other εμπεριέχει :values.',
    'required_with'        => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχει :values.',
    'required_with_all'    => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχουν :values.',
    'required_without'     => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει κανένα από :values.',
    'same'                 => 'Τα πεδία :attribute και :other πρέπει να είναι ίδια.',
    'size'                 => [
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι :size.',
        'file'    => 'Το πεδίο :attribute πρέπει να είναι :size kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι <strong>:size characters</strong>.',
        'array'   => 'Το πεδίο :attribute πρέπει να περιέχει :size αντικείμενα.',
    ],
    'string'               => 'Το :attribute πρέπει να είναι ένα <strong>string</strong>.',
    'timezone'             => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη ζώνη ώρας.',
    'unique'               => 'Το πεδίο :attribute <strong>χρησιμοποιείται</strong>.',
    'uploaded'             => 'Το :attribute <strong>απέτυχε</strong> να μεταφορτωθεί.',
    'url'                  => 'Η μορφή του :attribute είναι <strong>μή έγκυρη</strong>.',

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
            'rule-name'             => 'προσαρμοσμένο-μήνυμα',
        ],
        'invalid_currency'      => 'Το πεδίο :attribute δεν είναι έγκυρος κωδικός νομίσματος.',
        'invalid_amount'        => 'Το πεδίο :attribute δεν είναι έγκυρο ποσό.',
        'invalid_extension'     => 'Η επέκταση του αρχείου δεν είναι έγκυρη.',
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
