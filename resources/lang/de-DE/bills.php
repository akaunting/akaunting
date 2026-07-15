<?php

return [

    'bill_number'           => 'Eingangsrechnungsnummer',
    'bill_date'             => 'Eingangsrechnungsdatum',
    'bill_amount'           => 'Eingangsrechnungsbetrag',
    'total_price'           => 'Gesamtpreis',
    'due_date'              => 'Fälligkeitsdatum',
    'order_number'          => 'Bestellnummer',
    'bill_from'             => 'Eingangsrechnung von',

    'quantity'              => 'Menge',
    'price'                 => 'Preis',
    'sub_total'             => 'Zwischensumme',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Positionsrabatt',
    'tax_total'             => 'Steuern gesamt',
    'total'                 => 'Gesamt',

    'item_name'             => 'Artikelname|Artikelnamen',
    'recurring_bills'       => 'Wiederkehrende Eingangsrechnung|Wiederkehrende Eingangsrechnungen',

    'show_discount'         => ':discount% Rabatt',
    'add_discount'          => 'Rabatt hinzufügen',
    'discount_desc'         => 'der Zwischensumme',

    'payment_made'          => 'Zahlung erfolgt',
    'payment_due'           => 'Fälligkeit der Zahlung',
    'amount_due'            => 'Fälliger Betrag',
    'paid'                  => 'Bezahlt',
    'histories'             => 'Historie',
    'payments'              => 'Zahlungen',
    'add_payment'           => 'Zahlung hinzufügen',
    'mark_paid'             => 'Als bezahlt markieren',
    'mark_received'         => 'Als erhalten markieren',
    'mark_cancelled'        => 'Als storniert markieren',
    'download_pdf'          => 'Als PDF herunterladen',
    'send_mail'             => 'E-Mail senden',
    'create_bill'           => 'Eingangsrechnung erstellen',
    'receive_bill'          => 'Eingangsrechnung erhalten',
    'make_payment'          => 'Zahlung vornehmen',

    'form_description' => [
        'billing'           => 'Rechnungsdetails erscheinen in Ihrer Eingangsrechnung. Das Eingangsrechnungsdatum wird im Dashboard und in Berichten verwendet. Wählen Sie das voraussichtliche Zahlungsdatum als Fälligkeitsdatum aus.',
    ],

    'messages' => [
        'draft'             => 'Dies ist eine <b>ENTWURFS</b>-Eingangsrechnung. Sie erscheint in den Diagrammen, nachdem sie als erhalten markiert wurde.',

        'status' => [
            'created'       => 'Erstellt am :date',
            'receive' => [
                'draft'     => 'Nicht erhalten',
                'received'  => 'Erhalten am :date',
            ],
            'paid' => [
                'await'     => 'Zahlung ausstehend',
            ],
        ],
    ],

];
