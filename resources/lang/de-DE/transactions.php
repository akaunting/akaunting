<?php

return [

    'payment_received'      => 'Zahlung erhalten',
    'payment_made'          => 'Zahlung erfolgt',
    'paid_by'               => 'Bezahlt von',
    'paid_to'               => 'Bezahlt am',
    'related_invoice'       => 'Zugehörige Rechnung',
    'related_bill'          => 'Zugehörige Rechnung (Ausgabe)',
    'recurring_income'      => 'Wiederkehrende Einnahmen',
    'recurring_expense'     => 'Wiederkehrende Ausgaben',

    'form_description' => [
        'general'           => 'Hier können Sie die allgemeinen Transaktionsinformationen wie Datum, Betrag, Konto, Beschreibung usw. eingeben.',
        'assign_income'     => 'Wählen Sie eine Kategorie und einen Kunden aus, um Ihre Berichte detaillierter zu gestalten.',
        'assign_expense'    => 'Wählen Sie eine Kategorie und einen Lieferanten, um Ihre Berichte detaillierter zu gestalten.',
        'other'             => 'Geben Sie eine Referenz ein, um die Transaktion mit Ihren Datensätzen zu verknüpfen.',
    ],

    'slider' => [
        'create'            => ':user hat diese Transaktion am :date erstellt',
        'attachments'       => 'Laden Sie die Dateien dieser Transaktion herunter',
        'create_recurring'  => ':user hat diese wiederkehrende Transaktion am :date erstellt',
        'schedule'          => 'Wiederhole alle :interval :frequency seit :date',
        'children'          => ':count Transaktionen wurden automatisch erstellt',
        'transfer_headline' => 'Von :from_account an :to_account',
        'transfer_desc'     => 'Am :date erstellte Überweisung',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Ihr Kunde kann die Transaktion unter diesem Link ansehen',
            'copy_link'     => 'Kopieren Sie den Link und teilen Sie ihn mit Ihrem Kunden.',
        ],

        'expense' => [
            'show_link'     => 'Ihr Lieferant kann die Transaktion unter diesem Link ansehen',
            'copy_link'     => 'Kopieren und teilen Sie den Link mit Ihrem Lieferanten.',
        ],
    ],

    'sticky' => [
        'description'       => 'Sie zeigen eine Vorschau an, wie Ihr Kunde die Webversion Ihrer Zahlung sehen wird.',
    ],

];
