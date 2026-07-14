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

    'accepted' => 'Το πεδίο :attribute πρέπει να γίνει αποδεκτό.',
    'accepted_if' => 'Το :attribute πρέπει να γίνει αποδεκτό όταν :other είναι :value.',
    'active_url' => 'Το πεδίο :attribute δεν είναι αποδεκτή διεύθυνση URL.',
    'after' => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία μετά από :date.',
    'after_or_equal' => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία ίδια ή μετά από :date.',
    'alpha' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς, και παύλες.',
    'alpha_num' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array' => 'Το πεδίο :attribute πρέπει να είναι ένας πίνακας.',
    'before' => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία πριν από :date.',
    'before_or_equal' => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία ίδια ή πριν από :date.',
    'between' => [
        'array' => 'Το πεδίο :attribute πρέπει να έχει μεταξύ :min - :max αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max χαρακτήρες.',
    ],
    'boolean' => 'Το πεδίο :attribute πρέπει να είναι true ή false.',
    'confirmed' => 'Η επιβεβαίωση του :attribute δεν ταιριάζει.',
    'current_password' => 'Ο κωδικός πρόσβασης είναι εσφαλμένος.',
    'date' => 'Το πεδίο :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_equals' => 'Το πεδίο :attribute πρέπει να είναι μία ημερομηνία ίση με :date.',
    'date_format' => 'Το πεδίο :attribute δεν είναι της μορφής :format.',
    'declined' => 'Το πεδίο :attribute πρέπει να απορριφθεί.',
    'declined_if' => 'Το πεδίο :attribute πρέπει να απορριφθεί όταν το :other είναι :value.',
    'different' => 'Τα πεδία :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits' => 'Το πεδίο :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max ψηφία.',
    'dimensions' => 'Το πεδίο :attribute περιέχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct' => 'Το πεδίο :attribute περιέχει δύο φορές την ίδια τιμή.',
    'doesnt_start_with' => 'Το :attribute δεν μπορεί να ξεκινήσει με ένα από τα εξής: :values.',
    'double' => 'Το πεδίο :attribute πρέπει να είναι έγκυρος αριθμός διπλής ακρίβειας.',
    'email' => 'Το πεδίο :attribute πρέπει να είναι έγκυρη διεύθυνση email.',
    'ends_with' => 'Το :attribute πρέπει να τελειώνει με ένα από τα παρακάτω: :values',
    'enum' => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'exists' => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'file' => 'Το πεδίο :attribute πρέπει να είναι αρχείο.',
    'filled' => 'Το πεδίο :attribute πρέπει να έχει τιμή.',
    'gt' => [
        'array' => 'Το :attribute πρέπει να περιέχει περισσότερα από :value αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value KB.',
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει περισσότερους από :value χαρακτήρες.',
    ],
    'gte' => [
        'array' => 'Το :attribute πρέπει να έχει :value αντικείμενα ή παραπάνω.',
        'file' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value kilobytes.',
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value.',
        'string' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value χαρακτήρες.',
    ],
    'image' => 'Το πεδίο :attribute πρέπει να είναι εικόνα.',
    'in' => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'in_array' => 'Το πεδίο :attribute δεν υπάρχει σε :other.',
    'in_detailed' => 'Η τιμή ":value" του πεδίου :attribute δεν είναι έγκυρη. Αναμενόταν μία από τις τιμές: :values',
    'integer' => 'Το πεδίο :attribute πρέπει να είναι ακέραιος.',
    'ip' => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη διεύθυνση IP.',
    'ipv4' => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv4.',
    'ipv6' => 'Το :attribute πρέπει να είναι μια έγκυρη IPv6 διεύθυνση.',
    'json' => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη συμβολοσειρά JSON.',
    'lt' => [
        'array' => 'Το πεδίο :attribute πρέπει να έχει λιγότερα από :value αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μικρότερο από :value KB.',
        'numeric' => 'Το :attribute πρέπει να είναι μικρότερο από :value.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει λιγότερους από :value χαρακτήρες.',
    ],
    'lte' => [
        'array' => 'Το πεδίο :attribute δεν πρέπει να έχει περισσότερα από :value αντικείμενα.',
        'file' => 'Το :attribute πρέπει να είναι μικρότερο ή ίσο με :value kilobytes.',
        'numeric' => 'Το :attribute πρέπει να είναι μικρότερο ή ίσο με :value.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει έως :value χαρακτήρες.',
    ],
    'mac_address' => 'Το πεδίο :attribute πρέπει να είναι έγκυρη διεύθυνση MAC.',
    'max' => [
        'array' => 'Το πεδίο :attribute δεν μπορεί να έχει περισσότερα από :max αντικείμενα.',
        'file' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερό :max kilobytes.',
        'numeric' => 'Το πεδίο :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'string' => 'Το πεδίο :attribute δεν μπορεί να έχει περισσότερους από :max χαρακτήρες.',
    ],
    'mimes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'mimetypes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min' => [
        'array' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min χαρακτήρες.',
    ],
    'multiple_of' => 'Το πεδίο :attribute πρέπει να είναι πολλαπλάσιο του :value.',
    'not_in' => 'Το επιλεγμένο :attribute δεν είναι αποδεκτό.',
    'not_regex' => 'Η μορφή :attribute δεν είναι έγκυρη.',
    'numeric' => 'Το πεδίο :attribute πρέπει να είναι αριθμός.',
    'password' => [
        'letters' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα γράμμα.',
        'mixed' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα κεφαλαίο και ένα πεζό γράμμα.',
        'numbers' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον έναν αριθμό.',
        'symbols' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα σύμβολο.',
        'uncompromised' => 'Το πεδίο :attribute έχει εμφανιστεί σε διαρροή δεδομένων. Επιλέξτε διαφορετικό :attribute.',
    ],
    'present' => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'prohibited' => 'Το πεδίο :attribute απαγορεύεται.',
    'prohibited_if' => 'Το πεδίο :attribute απαγορεύεται όταν το :other είναι :value.',
    'prohibited_unless' => 'Το πεδίο :attribute απαγορεύεται εκτός αν το :other εμπεριέχει :values.',
    'prohibits' => 'Το πεδίο :attribute απαγορεύει την παρουσία του :other.',
    'regex' => 'Η μορφή του :attribute δεν είναι έγκυρη.',
    'required' => 'Το πεδίο :attribute είναι υποχρεωτικό.',
    'required_array_keys' => 'Το πεδίο :attribute πρέπει να περιέχει καταχωρήσεις για: :values.',
    'required_if' => 'Το πεδίο :attribute είναι απαραίτητο όταν το πεδίο :other είναι :value.',
    'required_unless' => 'Το πεδίο :attribute είναι απαραίτητο εκτός αν το πεδίο :other εμπεριέχει :values.',
    'required_with' => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχει :values.',
    'required_with_all' => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχουν :values.',
    'required_without' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει κανένα από :values.',
    'same' => 'Τα πεδία :attribute και :other πρέπει να είναι ίδια.',
    'size' => [
        'array' => 'Το πεδίο :attribute πρέπει να περιέχει :size αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι :size kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι :size.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει :size χαρακτήρες.',
    ],
    'starts_with' => 'Το :attribute πρέπει να αρχίζει με μια από τις ακόλουθες τιμές: :values.',
    'string' => 'Το πεδίο :attribute πρέπει να είναι συμβολοσειρά.',
    'timezone' => 'Το πεδίο :attribute πρέπει να είναι μία έγκυρη ζώνη ώρας.',
    'unique' => 'Η τιμή του πεδίου :attribute χρησιμοποιείται ήδη.',
    'uploaded' => 'Η μεταφόρτωση του πεδίου :attribute απέτυχε.',
    'url' => 'Το πεδίο :attribute πρέπει να είναι έγκυρη διεύθυνση URL.',
    'uuid' => 'Το πεδίο :attribute πρέπει να είναι έγκυρο UUID.',

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
            'rule-name' => 'προσαρμοσμένο-μήνυμα',
        ],
        'invalid_currency'      => 'Το πεδίο :attribute δεν είναι έγκυρος κωδικός νομίσματος.',
        'invalid_amount'        => 'Το πεδίο :attribute δεν είναι έγκυρο ποσό.',
        'invalid_quantity'      => 'Το πεδίο :attribute δεν είναι έγκυρη μαθηματική παράσταση.',
        'invalid_extension'     => 'Η επέκταση του αρχείου δεν είναι έγκυρη.',
        'invalid_dimension'     => 'Οι διαστάσεις του πεδίου :attribute πρέπει να είναι το πολύ :width x :height px.',
        'invalid_colour'        => 'Το χρώμα του πεδίου :attribute δεν είναι έγκυρο.',
        'invalid_payment_method'=> 'Η μέθοδος πληρωμής δεν είναι έγκυρη.',
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
