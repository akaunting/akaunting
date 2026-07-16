<?php

return [

    'whoops'              => 'Ωχ!',
    'hello'               => 'Γεια σας!',
    'salutation'          => 'Με εκτίμηση,<br> :company_name',
    'subcopy'             => 'Αν αντιμετωπίζετε πρόβλημα κάνοντας κλικ στο κουμπί ":text", αντιγράψτε και επικολλήστε την παρακάτω διεύθυνση URL στο πρόγραμμα περιήγησής σας: [:url](:url)',
    'mark_read'           => 'Σημείωση ως αναγνωσμένο',
    'mark_read_all'       => 'Σημείωση όλων ως αναγνωσμένων',
    'empty'               => 'Τέλεια, καμία ειδοποίηση!',
    'new_apps'            => 'Η εφαρμογή :app είναι διαθέσιμη. <a href=":url">Δείτε την τώρα</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Η ενημέρωση απέτυχε στο :domain',
            'description'   => 'Η ενημέρωση του :alias από :current_version σε :new_version απέτυχε στο βήμα <strong>:step</strong> με το ακόλουθο μήνυμα: :error_message',

        ],

        'slack' => [

            'description'   => 'Η ενημέρωση απέτυχε στο :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Η λήψη είναι έτοιμη',
            'description'   => 'Το αρχείο είναι έτοιμο για λήψη από τον ακόλουθο σύνδεσμο:',

        ],

        'failed' => [

            'title'         => 'Η λήψη απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η δημιουργία του αρχείου λόγω του ακόλουθου προβλήματος:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Η εισαγωγή ολοκληρώθηκε',
            'description'   => 'Η εισαγωγή ολοκληρώθηκε και οι εγγραφές είναι διαθέσιμες στον πίνακά σας.',

        ],

        'failed' => [

            'title'         => 'Η εισαγωγή απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η εισαγωγή του αρχείου λόγω των ακόλουθων προβλημάτων:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Η εξαγωγή είναι έτοιμη',
            'description'   => 'Το αρχείο εξαγωγής είναι έτοιμο για λήψη από τον ακόλουθο σύνδεσμο:',

        ],

        'failed' => [

            'title'         => 'Η εξαγωγή απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η δημιουργία του αρχείου εξαγωγής λόγω του ακόλουθου προβλήματος:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Μη έγκυρο email :type',
            'description'   => 'Η διεύθυνση email :email αναφέρθηκε ως μη έγκυρη και το άτομο απενεργοποιήθηκε. Ελέγξτε το ακόλουθο μήνυμα σφάλματος και διορθώστε τη διεύθυνση email:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Η λήψη είναι έτοιμη',
            'description'   => 'Το αρχείο <strong>:type</strong> είναι έτοιμο για <a href=":url" target="_blank"><strong>λήψη</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Η λήψη απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η δημιουργία του αρχείου λόγω διαφόρων προβλημάτων. Ελέγξτε το email σας για λεπτομέρειες.',

        ],

        'export_completed' => [

            'title'         => 'Η εξαγωγή είναι έτοιμη',
            'description'   => 'Το αρχείο εξαγωγής <strong>:type</strong> είναι έτοιμο για <a href=":url" target="_blank"><strong>λήψη</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Η εξαγωγή απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η δημιουργία του αρχείου εξαγωγής λόγω διαφόρων προβλημάτων. Ελέγξτε το email σας για λεπτομέρειες.',

        ],

        'import_completed' => [

            'title'         => 'Η εισαγωγή ολοκληρώθηκε',
            'description'   => 'Εισήχθησαν επιτυχώς <strong>:count</strong> γραμμές δεδομένων <strong>:type</strong>.',

        ],

        'import_failed' => [

            'title'         => 'Η εισαγωγή απέτυχε',
            'description'   => 'Δεν ήταν δυνατή η εισαγωγή του αρχείου λόγω διαφόρων προβλημάτων. Ελέγξτε το email σας για λεπτομέρειες.',

        ],

        'new_apps' => [

            'title'         => 'Νέα εφαρμογή',
            'description'   => 'Η εφαρμογή <strong>:name</strong> κυκλοφόρησε. Μπορείτε να <a href=":url">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Νέο τιμολόγιο',
            'description'   => 'Το τιμολόγιο <strong>:invoice_number</strong> δημιουργήθηκε. Μπορείτε να <a href=":invoice_portal_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Εκπρόθεσμο τιμολόγιο',
            'description'   => 'Το τιμολόγιο <strong>:invoice_number</strong> έληξε στις <strong>:invoice_due_date</strong>. Μπορείτε να <a href=":invoice_portal_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Εκπρόθεσμο τιμολόγιο',
            'description'   => 'Το τιμολόγιο <strong>:invoice_number</strong> έληξε στις <strong>:invoice_due_date</strong>. Μπορείτε να <a href=":invoice_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Νέο επαναλαμβανόμενο τιμολόγιο',
            'description'   => 'Το τιμολόγιο <strong>:invoice_number</strong> δημιουργήθηκε βάσει του επαναλαμβανόμενου κύκλου σας. Μπορείτε να <a href=":invoice_portal_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Νέο επαναλαμβανόμενο τιμολόγιο',
            'description'   => 'Το τιμολόγιο <strong>:invoice_number</strong> δημιουργήθηκε βάσει του επαναλαμβανόμενου κύκλου του πελάτη <strong>:customer_name</strong>. Μπορείτε να <a href=":invoice_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Το τιμολόγιο προβλήθηκε',
            'description'   => 'Ο πελάτης <strong>:customer_name</strong> είδε το τιμολόγιο <strong>:invoice_number</strong>. Μπορείτε να <a href=":invoice_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Η πληρωμή ελήφθη',
            'description'   => 'Σας ευχαριστούμε για την πληρωμή του τιμολογίου <strong>:invoice_number</strong>. Μπορείτε να <a href=":invoice_portal_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Η πληρωμή ελήφθη',
            'description'   => 'Σας ευχαριστούμε για την πληρωμή του τιμολογίου <strong>:invoice_number</strong>. Μπορείτε να <a href=":invoice_portal_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Η πληρωμή ελήφθη',
            'description'   => 'Ο πελάτης :customer_name κατέγραψε πληρωμή για το τιμολόγιο <strong>:invoice_number</strong>. Μπορείτε να <a href=":invoice_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Εκπρόθεσμο τιμολόγιο αγοράς',
            'description'   => 'Το τιμολόγιο αγοράς <strong>:bill_number</strong> έληξε στις <strong>:bill_due_date</strong>. Μπορείτε να <a href=":bill_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Νέο επαναλαμβανόμενο τιμολόγιο αγοράς',
            'description'   => 'Το τιμολόγιο αγοράς <strong>:bill_number</strong> δημιουργήθηκε βάσει του επαναλαμβανόμενου κύκλου του προμηθευτή <strong>:vendor_name</strong>. Μπορείτε να <a href=":bill_admin_link">κάνετε κλικ εδώ</a> για να δείτε τις λεπτομέρειες.',

        ],

        'invalid_email' => [

            'title'         => 'Μη έγκυρο email :type',
            'description'   => 'Η διεύθυνση email <strong>:email</strong> αναφέρθηκε ως μη έγκυρη και το άτομο απενεργοποιήθηκε. Ελέγξτε και διορθώστε τη διεύθυνση email.',

        ],

    ],

    'messages' => [

        'mark_read'             => 'Το :type ανέγνωσε αυτή την ειδοποίηση!',
        'mark_read_all'         => 'Το :type ανέγνωσε όλες τις ειδοποιήσεις!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Ρύθμιση εικονιδίων Firefox',
            'description'  => '<span class="font-medium">Αν τα εικονίδια δεν εμφανίζονται:</span> <br /> <span class="font-medium">Επιτρέψτε στις σελίδες να επιλέγουν τις δικές τους γραμματοσειρές αντί για τις παραπάνω επιλογές σας</span> <br /><br /> <span class="font-bold"> Ρυθμίσεις (Προτιμήσεις) > Γραμματοσειρές > Για προχωρημένους </span>',

        ],

    ],

];
