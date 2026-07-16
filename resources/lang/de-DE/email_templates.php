<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Rechnung {invoice_number} erstellt',
        'body'          => 'Hallo {customer_name},<br /><br />Wir haben Ihnen folgende Rechnung vorbereitet: <strong>{invoice_number}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns bei Fragen zu kontaktieren.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Rechnung {invoice_number} ist überfällig',
        'body'          => 'Hallo {customer_name},<br /><br />Die Rechnung <strong>{invoice_number}</strong> ist bis heute noch nicht bezahlt worden.<br /><br />Der Rechnungsbetrag beträgt {invoice_total} und war fällig am <strong>{invoice_due_date}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Rechnung {invoice_number} ist überfällig',
        'body'          => 'Hallo,<br /><br />{customer_name} hat eine Mitteilung für die überfällige Rechnung <strong>{invoice_number}</strong> erhalten.<br /><br />Der Rechnungsbetrag beträgt {invoice_total} und war fällig am <strong>{invoice_due_date}</strong>.<br /><br />Sie können die Rechnungsdaten unter folgendem Link einsehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Rechnung {invoice_number} – wiederkehrende Rechnung erstellt',
        'body'          => 'Hallo {customer_name},<br /><br />Basierend auf Ihrem Wiederholungszyklus haben wir für Sie folgende Rechnung vorbereitet: <strong>{invoice_number}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns bei Fragen zu kontaktieren.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Rechnung {invoice_number} – wiederkehrende Rechnung erstellt',
        'body'          => 'Hallo,<br /><br />Basierend auf {customer_name}s Wiederholungszyklus wurde die <strong>{invoice_number}</strong>-Rechnung automatisch erstellt.<br /><br />Sie können die Rechnungsdaten unter folgendem Link einsehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Rechnung {invoice_number} angesehen',
        'body'          => 'Hallo,<br /><br />{customer_name} hat die Rechnung <strong>{invoice_number}</strong> angesehen.<br /><br />Sie können die Rechnungsdetails unter folgendem Link einsehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Ihre Quittung für Rechnung {invoice_number}',
        'body'          => 'Hallo {customer_name},<br /><br />Vielen Dank für die Zahlung. Sie finden die Zahlungsinformationen unten:<br /><br />-------------------------------------------------<br />Betrag: <strong>{transaction_total}</strong><br />Datum: <strong>{transaction_paid_date}</strong><br />Rechnungsnummer: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Sie können die Rechnungsdetails jederzeit unter folgendem Link einsehen: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns bei Fragen zu kontaktieren.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Zahlung für Rechnung {invoice_number} erhalten',
        'body'          => 'Hallo,<br /><br />{customer_name} hat die Rechnung <strong>{invoice_number}</strong> bezahlt.<br /><br />Sie können die Rechnungsdaten unter folgendem Link einsehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Eingangsrechnung {bill_number} ist überfällig',
        'body'          => 'Hallo,<br /><br />Diese Eingangsrechnung <strong>{bill_number}</strong> von {vendor_name} ist überfällig.<br /><br />Der Rechnungsbetrag beträgt insgesamt {bill_total} und ist fällig am <strong>{bill_due_date}</strong>.<br /><br />Die Details der Eingangsrechnung sehen Sie unter folgendem Link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Eingangsrechnung {bill_number} – wiederkehrende Eingangsrechnung erstellt',
        'body'          => 'Hallo,<br /><br />Basierend auf {vendor_name}s Wiederholungszyklus wurde die <strong>{bill_number}</strong>-Eingangsrechnung automatisch erstellt.<br /><br />Sie können die Details der Eingangsrechnung unter folgendem Link einsehen: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Ihre Quittung von {company_name}',
        'body'          => 'Hallo {contact_name},<br /><br />Vielen Dank für die Zahlung. <br /><br />Sie können die Zahlungsdetails unter folgendem Link einsehen: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sie können uns gerne bei Fragen kontaktieren.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Zahlung erfolgt durch {company_name}',
        'body'          => 'Hallo {contact_name},<br /><br />wir haben die folgende Zahlung geleistet. <br /><br />Sie können die Zahlungsdetails unter folgendem Link einsehen: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sie können uns gerne bei Fragen kontaktieren.<br /><br />Mit freundlichen Grüßen,<br />{company_name}',
    ],
];
