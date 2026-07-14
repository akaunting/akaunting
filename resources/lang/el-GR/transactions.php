<?php

return [

    'payment_received'      => 'Ληφθείσα πληρωμή',
    'payment_made'          => 'Πραγματοποιηθείσα πληρωμή',
    'paid_by'               => 'Πληρώθηκε από',
    'paid_to'               => 'Πληρώθηκε προς',
    'related_invoice'       => 'Σχετικό τιμολόγιο',
    'related_bill'          => 'Σχετικός λογαριασμός',
    'recurring_income'      => 'Επαναλαμβανόμενο έσοδο',
    'recurring_expense'     => 'Επαναλαμβανόμενο έξοδο',
    'included_tax'          => 'Συμπεριλαμβανόμενο ποσό φόρου',
    'connected'             => 'Συνδεδεμένο',
    'connect_message'       => 'Οι φόροι για αυτό το :type δεν υπολογίστηκαν κατά τη διαδικασία σύνδεσης. Οι φόροι δεν μπορούν να συνδεθούν.',

    'form_description' => [
        'general'           => 'Εδώ μπορείτε να εισαγάγετε τις γενικές πληροφορίες της συναλλαγής, όπως ημερομηνία, ποσό, λογαριασμό, περιγραφή κ.λπ.',
        'assign_income'     => 'Επιλέξτε κατηγορία και πελάτη για πιο λεπτομερείς αναφορές.',
        'assign_expense'    => 'Επιλέξτε κατηγορία και προμηθευτή για πιο λεπτομερείς αναφορές.',
        'other'             => 'Εισαγάγετε αριθμό και αναφορά για να διατηρήσετε τη συναλλαγή συνδεδεμένη με τις εγγραφές σας.',
    ],

    'slider' => [
        'create'            => 'Ο χρήστης :user δημιούργησε αυτή τη συναλλαγή στις :date',
        'attachments'       => 'Κάντε λήψη των αρχείων που επισυνάπτονται σε αυτή τη συναλλαγή',
        'create_recurring'  => 'Ο χρήστης :user δημιούργησε αυτό το επαναλαμβανόμενο πρότυπο στις :date',
        'schedule'          => 'Επανάληψη κάθε :interval :frequency από :date',
        'children'          => 'Δημιουργήθηκαν αυτόματα :count συναλλαγές',
        'connect'           => 'Αυτή η συναλλαγή είναι συνδεδεμένη με :count συναλλαγές',
        'transfer_headline' => '<div> <span class="font-bold"> Από: </span> :from_account </div> <div> <span class="font-bold"> προς: </span> :to_account </div>',
        'transfer_desc'     => 'Η μεταφορά δημιουργήθηκε στις :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Ο πελάτης σας μπορεί να δει τη συναλλαγή σε αυτόν τον σύνδεσμο',
            'copy_link'     => 'Αντιγράψτε τον σύνδεσμο και κοινοποιήστε τον στον πελάτη σας.',
        ],

        'expense' => [
            'show_link'     => 'Ο προμηθευτής σας μπορεί να δει τη συναλλαγή σε αυτόν τον σύνδεσμο',
            'copy_link'     => 'Αντιγράψτε τον σύνδεσμο και κοινοποιήστε τον στον προμηθευτή σας.',
        ],
    ],

    'sticky' => [
        'description'       => 'Βλέπετε σε προεπισκόπηση πώς θα εμφανίζεται στον πελάτη σας η διαδικτυακή έκδοση της πληρωμής.',
    ],

    'messages' => [
        'update_document_transaction' => 'Μπορείτε να ενημερώσετε αυτή τη συναλλαγή. Μεταβείτε στο έγγραφο και επεξεργαστείτε την εκεί.',
        'create_document_transaction_error' => 'Αυτό το τελικό σημείο δεν μπορεί να προστεθεί σε έγγραφο. Χρησιμοποιήστε {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Αυτό το τελικό σημείο δεν μπορεί να ενημερωθεί σε έγγραφο. Χρησιμοποιήστε {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Αυτό το τελικό σημείο δεν μπορεί να διαγραφεί από έγγραφο. Χρησιμοποιήστε {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
