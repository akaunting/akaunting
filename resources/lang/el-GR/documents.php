<?php

return [

    'edit_columns'              => 'Επεξεργασία Στηλών',
    'empty_items'               => 'Δεν έχετε προσθέσει κανένα στοιχείο.',
    'grand_total'               => 'Γενικό Σύνολο',
    'accept_payment_online'     => 'Αποδοχή Ηλεκτρονικών Πληρωμών',
    'transaction'               => 'Πραγματοποιήθηκε πληρωμή :amount μέσω του λογαριασμού :account.',
    'portal_transaction'        => 'Πραγματοποιήθηκε πληρωμή :amount μέσω :payment_method.',
    'billing'                   => 'Χρέωση',
    'advanced'                  => 'Για Προχωρημένους',

    'item_price_hidden'         => 'Αυτή η στήλη είναι κρυμμένη στο :type σας.',

    'actions' => [
        'cancel'                => 'Ακύρωση',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Εσείς</b> σημειώσατε αυτό το τιμολόγιο ως',
        'services'              => 'Υπηρεσίες',
        'another_item'          => 'Άλλο Στοιχείο',
        'another_description'   => 'και άλλη μια περιγραφή',
        'more_item'             => '+:count ακόμη στοιχείο',
    ],

    'statuses' => [
        'draft'                 => 'Πρόχειρο',
        'sent'                  => 'Απεσταλμένο',
        'expired'               => 'Ληγμένο',
        'viewed'                => 'Προβληθέν',
        'approved'              => 'Εγκεκριμένο',
        'received'              => 'Παραληφθέν',
        'refused'               => 'Απορριφθέν',
        'restored'              => 'Επαναστημένο',
        'reversed'              => 'Ανεστραμμένο',
        'partial'               => 'Μερικό',
        'paid'                  => 'Εξοφλημένο',
        'pending'               => 'Εκκρεμές',
        'invoiced'              => 'Τιμολογημένο',
        'overdue'               => 'Εκπρόθεσμο',
        'unpaid'                => 'Ανεξόφλητο',
        'cancelled'             => 'Ακυρωμένο',
        'voided'                => 'Μηδενισμένο',
        'completed'             => 'Ολοκληρωμένο',
        'shipped'               => 'Απεσταλμένο (Αποστολή)',
        'refunded'              => 'Επιστραφέν',
        'failed'                => 'Αποτυχημένο',
        'denied'                => 'Απορριφθέν (Άρνηση)',
        'processed'             => 'Επεξεργασμένο',
        'open'                  => 'Ανοιχτό',
        'closed'                => 'Κλειστό',
        'billed'                => 'Τιμολογημένο (Χρέωση)',
        'delivered'             => 'Παραδοθέν',
        'returned'              => 'Επιστραφέν',
        'drawn'                 => 'Εκδοθέν',
        'not_billed'            => 'Μη Τιμολογημένο',
        'issued'                => 'Εκδοθέν',
        'not_invoiced'          => 'Μη Τιμολογημένο',
        'confirmed'             => 'Επιβεβαιωμένο',
        'not_confirmed'         => 'Μη Επιβεβαιωμένο',
        'active'                => 'Ενεργό',
        'ended'                 => 'Ολοκληρωμένο (Λήξη)',
    ],

    'form_description' => [
        'companies'             => 'Αλλάξτε τη διεύθυνση, το λογότυπο και άλλες πληροφορίες της εταιρείας σας.',
        'billing'               => 'Τα στοιχεία χρέωσης εμφανίζονται στο έγγραφό σας.',
        'advanced'              => 'Επιλέξτε την κατηγορία, προσθέστε ή επεξεργαστείτε το υποσέλιδο και προσθέστε συνημμένα στο :type σας.',
        'attachment'            => 'Κάντε λήψη των αρχείων που επισυνάπτονται σε αυτό το :type',
    ],

    'slider' => [
        'create'            => 'Ο χρήστης :user δημιούργησε αυτό το :type στις :date',
        'create_recurring'  => 'Ο χρήστης :user δημιούργησε αυτό το επαναλαμβανόμενο πρότυπο στις :date',
        'send'              => 'Ο χρήστης :user έστειλε αυτό το :type στις :date',
        'schedule'          => 'Επανάληψη κάθε :interval :frequency από :date',
        'children'          => 'Δημιουργήθηκαν αυτόματα :count :type',
        'cancel'            => 'Ο χρήστης :user ακύρωσε αυτό το :type στις :date',
    ],

    'messages' => [
        'email_sent'            => 'Το email για :type στάλθηκε!',
        'restored'              => 'Το :type επαναφέρθηκε!',
        'marked_as'             => 'Το :type σημειώθηκε ως :status!',
        'marked_sent'           => 'Το :type σημειώθηκε ως απεσταλμένο!',
        'marked_paid'           => 'Το :type σημειώθηκε ως εξοφλημένο!',
        'marked_viewed'         => 'Το :type σημειώθηκε ως προβληθέν!',
        'marked_cancelled'      => 'Το :type σημειώθηκε ως ακυρωμένο!',
        'marked_received'       => 'Το :type σημειώθηκε ως παραληφθέν!',
    ],

    'recurring' => [
        'auto_generated'        => 'Αυτόματη Δημιουργία',

        'tooltip' => [
            'document_date'     => 'Η ημερομηνία του :type θα οριστεί αυτόματα βάσει του χρονοδιαγράμματος και της συχνότητας του :type.',
            'document_number'   => 'Ο αριθμός του :type θα οριστεί αυτόματα όταν δημιουργείται κάθε επαναλαμβανόμενο :type.',
        ],
    ],

    'empty_attachments'         => 'Δεν υπάρχουν αρχεία συνημμένα σε αυτό το :type.',
];
