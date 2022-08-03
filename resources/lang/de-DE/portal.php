<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Rechnungen',
    'payments'              => 'Zahlungen',
    'payment_received'      => 'Zahlung eingegangen, vielen Dank.',
    'create_your_invoice'   => 'Erstellen Sie jetzt Ihre eigene Rechnung – kostenlos',
    'get_started'           => 'Kostenlos starten',
    'billing_address'       => 'Rechnungensadresse',
    'see_all_details'       => 'Alle Kontodetails anzeigen',
    'all_payments'          => 'Anmelden, um alle Zahlungen anzuzeigen',
    'received_date'         => 'Empfangsdatum',

    'last_payment'          => [
        'title'             => 'Letzte Zahlung',
        'description'       => 'Sie haben diese Zahlung am :date gemacht',
        'not_payment'       => 'Sie haben noch keine Zahlung getätigt.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Ausstehender Saldo',
        'description'       => 'Ihr ausstehender Kontostand ist:',
        'not_payment'       => 'Sie haben noch kein ausstehendes Guthaben.',
    ],

    'latest_invoices'       => [
        'title'             => 'Neueste Rechnungen',
        'description'       => ':date - Sie wurden mit Rechnungsnummer :invoice_number abgerechnet.',
        'no_data'           => 'Keine Rechnungen vorhanden.',
    ],

    'invoice_history'       => [
        'title'             => 'Rechnungsverlauf',
        'description'       => ':date - Sie wurden mit Rechnungsnummer :invoice_number abgerechnet.',
        'no_data'           => 'Sie haben noch keine Rechnungsverlauf.',
    ],

    'payment_history'       => [
        'title'             => 'Zahlungsverlauf',
        'description'       => ':date - Sie haben eine Zahlung von :amount getätigt.',
        'invoice_description'=> ':date - Sie haben :amount für die Rechnungsnummer :invoice_number bezahlt.',

        'no_data'           => 'Sie haben noch keinen Zahlungsverlauf.',
    ],

    'payment_detail'        => [
        'description'       => 'Sie haben :amount für diese Rechnung am :date bezahlt.'
    ],

];
