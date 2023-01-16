<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Το τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Αγαπητοί{customer_name},<br /><br />Έχουμε δημιουργήσει το παρακάτω τιμολόγιο για εσάς: <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή από τον παρακάτω σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για οποιαδήποτε απορία.<br /><br />Μετα τιμής,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Ενημέρωση εκπρόθεσμου τιμολογίου {invoice_number}',
        'body'          => 'Αγαπητοί{customer_name},<br /><br />Αυτή είναι μια ειδοποίηση οτι το τιμολόγιο <strong>{invoice_number}</strong> είναι εκπρόθεσμο.<br /><br />Το σύνολο του τιμολογίου είναι {invoice_total} και ήταν εμπρόθεσμο έως <strong>{invoice_due_date}</strong>.<br /><br />Μπορείτε να δείτε το τιμολόγιο και να προχωρήσετε στην πληρωμή από τον παρακάτω σύνδεσμο: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Ενημέρωση εκπρόθεσμου τιμολογίου {invoice_number}',
        'body'          => 'Γειά σας, ο πελάτης <br /><br />{customer_name} έλαβε μια ενημέρωση για λήξη προθεσμίας πληρωμής του <strong>{invoice_number}</strong> τιμολογίου.<br /><br />Το σύνολο είναι {invoice_total} και η προθεσμίς πληρωμής έληξε <strong>{invoice_due_date}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Το επαναλαμβανόμενο τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Αγαπητοί{customer_name},<br /><br />Βάσει των επαναλαμβανόμενων χρεώσεων σας έχουμε δημιουργήσει το παρακάτω τιμολόγιο για εσάς: <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες και να προχωρήσετε στην πληρωμή από τον παρακάτω σύνδεσμο:: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για οποιαδήποτε απορία.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Το επαναλαμβανόμενο τιμολόγιο {invoice_number} δημιουργήθηκε',
        'body'          => 'Γειά σας,<br /><br /> Βάσει των επαναλαμβανόμενων χρεώσεων του {customer_name}, <strong>{invoice_number}</strong> έχει παραχθεί αυτόματα ένα τιμολόγιο.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Ελήφθη πληρωμή για το τιμολόγιο {invoice_number}',
        'body'          => 'Αγαπητοί {customer_name},<br /><br />Ευχαριστούμε για την πληρωμή. Ακολουθούν οι λεπτομέρειες της πληρωμής:<br /><br />-------------------------------------------------<br /><br />Ποσό: <strong>{transaction_total}<br /></strong>Ημερμηνία: <strong>{transaction_paid_date}</strong><br />Αριθμός Τιμολογίου: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο:: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Μην διστάσετε να επικοινωνήσετε μαζί μας για οποιαδήποτε απορία.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Ελήφθη πληρωμή για το τιμολόγιο {invoice_number}',
        'body'          => 'Γειά σας,<br /><br />{customer_name} καταχωρήθηκε μια πληρωμή για το τιμολόγιο <strong>{invoice_number}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Ενημέρωση υπενθύμισης λογαριασμού {bill_number}',
        'body'          => 'Γειά σας,<br /><br />Αυτή ειναι μια υπενθύμιση για τον λογαριασμό <strong>{bill_number}</strong> της {vendor_name}.<br /><br />Το σύνολο του λογαριασμού είναι {bill_total} και είναι ληξιπρόθεσμος την <strong>{bill_due_date}</strong>.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Ο επαναλαμβανόμενος λογαριασμός {bill_number} δημιουργήθηκε',
        'body'          => 'Γειά σας,<br /><br /> Βάσει της επαναλαμβανόμενης χρέωσης με την {vendor_name}, <strong>{bill_number}</strong> δημιουργήθηκε αυτόματα ένα τιμολόγιο.<br /><br />Μπορείτε να δείτε τις λεπτομέρειες του τιμολογίου στον παρακάτω σύνδεσμο: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Μετα Τιμής,<br />{company_name}',
    ],

];
