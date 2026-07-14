<?php

return [

    'edit_columns'              => 'Επεξεργασία στηλών',
    'empty_items'               => 'Δεν έχετε προσθέσει κανένα στοιχείο.',
    'grand_total'               => 'Γενικό σύνολο',
    'accept_payment_online'     => 'Αποδοχή ηλεκτρονικών πληρωμών',
    'transaction'               => 'Πραγματοποιήθηκε πληρωμή :amount μέσω του λογαριασμού :account.',
    'portal_transaction'        => 'Πραγματοποιήθηκε πληρωμή :amount μέσω :payment_method.',
    'billing'                   => 'Χρέωση',
    'advanced'                  => 'Για προχωρημένους',

    'item_price_hidden'         => 'Αυτή η στήλη είναι κρυφή στο :type σας.',

    'actions' => [
        'cancel'                => 'Ακύρωση',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Εσείς</b> σημειώσατε αυτό το τιμολόγιο ως',
        'services'              => 'Υπηρεσίες',
        'another_item'          => 'Άλλο στοιχείο',
        'another_description'   => 'και άλλη μία περιγραφή',
        'more_item'             => '+:count ακόμη στοιχείο',
    ],

    'statuses' => [
        'draft'                 => 'Πρόχειρο',
        'sent'                  => 'Απεσταλμένο',
        'expired'               => 'Ληγμένο',
        'viewed'                => 'Προβλήθηκε',
        'approved'              => 'Εγκρίθηκε',
        'received'              => 'Παραλήφθηκε',
        'refused'               => 'Απορρίφθηκε',
        'restored'              => 'Επαναφέρθηκε',
        'reversed'              => 'Αντιστράφηκε',
        'partial'               => 'Μερικό',
        'paid'                  => 'Εξοφλημένο',
        'pending'               => 'Σε εκκρεμότητα',
        'invoiced'              => 'Τιμολογήθηκε',
        'overdue'               => 'Εκπρόθεσμο',
        'unpaid'                => 'Ανεξόφλητο',
        'cancelled'             => 'Ακυρώθηκε',
        'voided'                => 'Ακυρωμένο',
        'completed'             => 'Ολοκληρώθηκε',
        'shipped'               => 'Απεστάλη',
        'refunded'              => 'Επιστράφηκε',
        'failed'                => 'Απέτυχε',
        'denied'                => 'Απορρίφθηκε',
        'processed'             => 'Επεξεργάστηκε',
        'open'                  => 'Ανοιχτό',
        'closed'                => 'Κλειστό',
        'billed'                => 'Χρεώθηκε',
        'delivered'             => 'Παραδόθηκε',
        'returned'              => 'Επιστράφηκε',
        'drawn'                 => 'Εκδόθηκε',
        'not_billed'            => 'Δεν χρεώθηκε',
        'issued'                => 'Εκδόθηκε',
        'not_invoiced'          => 'Δεν τιμολογήθηκε',
        'confirmed'             => 'Επιβεβαιώθηκε',
        'not_confirmed'         => 'Δεν επιβεβαιώθηκε',
        'active'                => 'Ενεργό',
        'ended'                 => 'Έληξε',
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
        'auto_generated'        => 'Αυτόματη δημιουργία',

        'tooltip' => [
            'document_date'     => 'Η ημερομηνία του :type θα οριστεί αυτόματα βάσει του χρονοδιαγράμματος και της συχνότητας του :type.',
            'document_number'   => 'Ο αριθμός του :type θα οριστεί αυτόματα όταν δημιουργείται κάθε επαναλαμβανόμενο :type.',
        ],
    ],

    'empty_attachments'         => 'Δεν υπάρχουν αρχεία συνημμένα σε αυτό το :type.',
];
