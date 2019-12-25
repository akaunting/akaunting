<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Rechnung {invoice_number} erstellt',
        'body'          => 'Hallo {customer_name},<br /><br />Wir haben Ihnen folgende Rechnung vorbereitet: <strong>{invoice_number}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns für jede Frage zu kontaktieren.<br /><br />Herzliche Grüße,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Rechnung {invoice_number} ist überfällig',
        'body'          => 'Hallo {customer_name},<br /><br />Die Rechnung <strong>{invoice_number}</strong> ist bis heute noch nicht bezahlt worden.<br /><br />Der Rechnungsbetrag beträgt {invoice_total} und war fällig am <strong>{invoice_due_date}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Rechnung {invoice_number} ist überfällig',
        'body'          => 'Hallo,<br /><br />{customer_name} hat eine Mitteilung für die überfällige Rechnung <strong>{invoice_number}</strong> erhalten.<br /><br />Der Rechnungsbetrag beträgt {invoice_total} und war fällig am <strong>{invoice_due_date}</strong>.<br /><br />Sie können die Rechnungsdaten unter folgendem Link einsehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Rechnung {invoice_number} - wiederkehrende Rechnung erstellt',
        'body'          => 'Hallo {customer_name},<br /><br />Basierend auf Ihrem wiederkehrenden Kreis, wir haben für Sie folgende Rechnung vorbereitet: <strong>{invoice_number}</strong>.<br /><br />Sie können die Rechnungsdaten einsehen und mit der Zahlung fortfahren: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns für jede Frage zu kontaktieren.<br /><br />Herzliche Grüße,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Rechnung {invoice_number} - wiederkehrende Rechnung erstellt',
        'body'          => 'Hallo,<br /><br /> Basierend auf {customer_name} wiederkehrenden Kreis, <strong>{invoice_number}</strong> Rechnung wurde automatisch erstellt.<br /><br />Sie können die Rechnungsdaten unter folgendem Link sehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Zahlung für Rechnung {invoice_number} erhalten',
        'body'          => 'Hallo {customer_name},<br /><br />Vielen Dank für die Zahlung. Sie finden die Zahlungsinformationen unten:<br /><br />-------------------------------------------------<br /><br />Betrag: <strong>{transaction_total}<br /></strong>Datum: <strong>{transaction_paid_date}</strong><br />Rechnungsnummer: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Sie können die Rechnungsdetails immer unter folgendem Link sehen: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Zögern Sie nicht, uns für jede Frage zu kontaktieren.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Zahlung für Rechnung {invoice_number} erhalten',
        'body'          => 'Hallo,<br /><br />{customer_name} hat die Rechnung <strong>{invoice_number}</strong> bezhalt.<br /><br />Sie können die Rechnungsdaten unter folgendem Link sehen: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Die Rechnung {bill_number} ist überfällig (Ausgaben)',
        'body'          => 'Hallo,<br /><br />Dies Rechnung <strong>{bill_number}</strong> von {vendor_name} ist überfällig.<br /><br />Die Rechnung beträgt insgesamt {bill_total} und ist fällig <strong>{bill_due_date}</strong>.<br /><br />Die Details der Rechnung sehen Sie unter folgendem Link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Rechnung {bill_number} - wiederkehrende Rechnung (Ausgaben)',
        'body'          => 'Hallo,<br /><br /> Basierend auf {vendor_name} wiederkehrenden Kreis, <strong>{bill_number}</strong> Rechnung wurde automatisch erstellt.<br /><br />Sie können die Details der Rechnung unter folgendem Link sehen: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Beste Grüße,<br />{company_name}',
    ],

];
