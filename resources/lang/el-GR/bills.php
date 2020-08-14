<?php

return [

    'bill_number'           => 'Αριθμό λογαριασμού',
    'bill_date'             => 'Ημερομηνία Λογαριασμού',
    'total_price'           => 'Συνολική Τιμή',
    'due_date'              => 'Ημ/νία παράδοσης',
    'order_number'          => 'Αριθμός παραγγελίας',
    'bill_from'             => 'Λογαριασμός από',

    'quantity'              => 'Ποσότητα',
    'price'                 => 'Τιμή',
    'sub_total'             => 'Μερικό Σύνολο',
    'discount'              => 'Έκπτωση',
    'item_discount'         => 'Έκπτωση',
    'tax_total'             => 'Συνολικό ΦΠΑ',
    'total'                 => 'Σύνολο',

    'item_name'             => 'Όνομα/ονόματα Αντικειμένου',

    'show_discount'         => ':discount % Έκπτωση',
    'add_discount'          => 'Προσθήκη έκπτωσης',
    'discount_desc'         => 'του μερικού συνόλου',

    'payment_due'           => 'Προθεσμία εξόφλησης',
    'amount_due'            => 'Οφειλόμενο ποσό',
    'paid'                  => 'Πληρωτέο',
    'histories'             => 'Ιστορικό',
    'payments'              => 'Πληρωμές',
    'add_payment'           => 'Προσθήκη πληρωμής',
    'mark_paid'             => 'Πληρώθηκε',
    'mark_received'         => 'Ειλημμένη σημείωση',
    'mark_cancelled'        => 'Ακύρωση',
    'download_pdf'          => 'Λήψη PDF',
    'send_mail'             => 'Αποστολή Email',
    'create_bill'           => 'Δημιουργία Λογαριασμού',
    'receive_bill'          => 'Παραλαβή Λογαριασμού',
    'make_payment'          => 'Δημιουργία Πληρωμής',

    'statuses' => [
        'draft'             => 'Προσχέδιο',
        'received'          => 'Ληφθέντα',
        'partial'           => 'Μερική',
        'paid'              => 'Πληρωμένο',
        'overdue'           => 'Εκπρόθεσμο',
        'unpaid'            => 'Απλήρωτο',
        'cancelled'         => 'Άκυρο',
    ],

    'messages' => [
        'marked_received'   => 'Ο λογαριασμός επισημάνθηκε ως παραληφθείς!',
        'marked_paid'       => 'Ο λογαριασμός επισημάνθηκε ως πληρωμένος!',
        'marked_cancelled'  => 'Ο λογαριασμός επισημάνθηκε ως ακυρωμένος!',
        'draft'             => 'Αυτός ο λογαριασμός είναι <b>ΠΡΟΧΕΙΡΟΣ</b> και θα εμφανιστεί στα γραφήματα μετά την παραλαβή του.',

        'status' => [
            'created'       => 'Δημιουργήθηκε :date',
            'receive' => [
                'draft'     => 'Δεν εστάλη',
                'received'  => 'Ελήφθη :date',
            ],
            'paid' => [
                'await'     => 'Αναμένεται εξόφληση',
            ],
        ],
    ],

];
