<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Το τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Αγαπητέ/ή {customer_name},<br /><br />Έχουμε ετοιμάσει το ακόλουθο τιμολόγιο για εσάς: <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου και να προχωρήσετε στην πληρωμή από τον ακόλουθο σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για τυχόν ερωτήσεις.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Ενημέρωση εκπρόθεσμου τιμολογίου {invoice_number}',
        'body'          => 'Αγαπητέ/ή {customer_name},<br /><br />Αυτή είναι μια ειδοποίηση ότι το τιμολόγιο <strong>{invoice_number}</strong> είναι εκπρόθεσμο.<br /><br />Το σύνολο του τιμολογίου είναι {invoice_total} και ήταν πληρωτέο έως <strong>{invoice_due_date}</strong>.<br /><br />Μπορείτε να δείτε το τιμολόγιο και να προχωρήσετε στην πληρωμή από τον παρακάτω σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Ενημέρωση εκπρόθεσμου τιμολογίου {invoice_number}',
        'body'          => 'Γεια σας,<br /><br />Ο πελάτης {customer_name} έλαβε μια ενημέρωση για λήξη προθεσμίας πληρωμής του τιμολογίου <strong>{invoice_number}</strong>.<br /><br />Το σύνολο είναι {invoice_total} και η προθεσμία πληρωμής έληξε <strong>{invoice_due_date}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Το επαναλαμβανόμενο τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Αγαπητέ/ή {customer_name},<br /><br />Βάσει του επαναλαμβανόμενου κύκλου σας, έχουμε δημιουργήσει το παρακάτω τιμολόγιο για εσάς: <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή από τον παρακάτω σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για οποιαδήποτε απορία.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Το επαναλαμβανόμενο τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Γεια σας,<br /><br />Βάσει του επαναλαμβανόμενου κύκλου του {customer_name}, το <strong>{invoice_number}</strong> τιμολόγιο έχει παραχθεί αυτόματα.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Το τιμολόγιο {invoice_number} προβλήθηκε',
        'body'          => 'Γεια σας,<br /><br />Ο {customer_name} είδε το τιμολόγιο <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου από τον ακόλουθο σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Η απόδειψή σας για το τιμολόγιο {invoice_number}',
        'body'          => 'Αγαπητέ/ή {customer_name},<br /><br />Σας ευχαριστούμε για την πληρωμή. Ακολουθούν οι λεπτομέρειες της πληρωμής:<br /><br />-------------------------------------------------<br />Ποσό: <strong>{transaction_total}</strong><br />Ημερομηνία: <strong>{transaction_paid_date}</strong><br />Αριθμός τιμολογίου: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Μπορείτε πάντα να δείτε τις λεπτομέρειες του τιμολογίου από τον ακόλουθο σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για τυχόν ερωτήσεις.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Ελήφθη πληρωμή για το τιμολόγιο {invoice_number}',
        'body'          => 'Γεια σας,<br /><br />Ο {customer_name} καταχώρησε μια πληρωμή για το τιμολόγιο <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Υπενθύμιση τιμολογίου αγοράς {bill_number}',
        'body'          => 'Γεια σας,<br /><br />Αυτή είναι μια υπενθύμιση για το τιμολόγιο αγοράς <strong>{bill_number}</strong> του προμηθευτή {vendor_name}.<br /><br />Το σύνολο του τιμολογίου αγοράς είναι {bill_total} και είναι εκπρόθεσμο στις <strong>{bill_due_date}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου αγοράς στον παρακάτω σύνδεσμο: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Το επαναλαμβανόμενο τιμολόγιο αγοράς {bill_number} δημιουργήθηκε',
        'body'          => 'Γεια σας,<br /><br />Βάσει του επαναλαμβανόμενου κύκλου του προμηθευτή {vendor_name}, το <strong>{bill_number}</strong> τιμολόγιο αγοράς δημιουργήθηκε αυτόματα.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου αγοράς στον παρακάτω σύνδεσμο: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Απόδειψη από το {company_name}',
        'body'          => 'Αγαπητέ/ή {contact_name},<br /><br />Σας ευχαριστούμε για την πληρωμή. <br /><br />Μπορείτε να δείτε τις λεπτομέρειες της πληρωμής από τον ακόλουθο σύνδεσμο: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για τυχόν ερωτήσεις.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Πληρωμή από το {company_name}',
        'body'          => 'Αγαπητέ/ή {contact_name},<br /><br />Πραγματοποιήσαμε την ακόλουθη πληρωμή. <br /><br />Μπορείτε να δείτε τις λεπτομέρειες της πληρωμής από τον ακόλουθο σύνδεσμο: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για τυχόν ερωτήσεις.<br /><br />Με εκτίμηση,<br />{company_name}',
    ],
];
