<?php

return [

    'bill_number'           => 'Rechnungsnummer',
    'bill_date'             => 'Rechnungsdatum',
    'total_price'           => 'Gesamtpreis',
    'due_date'              => 'Fälligkeitsdatum',
    'order_number'          => 'Bestellnummer',
    'bill_from'             => 'Rechnung vom',

    'quantity'              => 'Menge',
    'price'                 => 'Preis',
    'sub_total'             => 'Zwischensumme',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Positions-Rabatt',
    'tax_total'             => 'Steuern Gesamt',
    'total'                 => 'Gesamt',

    'item_name'             => 'Artikel-Name|Artikel-Namen',

    'show_discount'         => ':discount% Rabatt',
    'add_discount'          => 'füge Rabatt hinzu',
    'discount_desc'         => 'der Zwischensumme',

    'payment_due'           => 'Fälligkeit der Zahlung',
    'amount_due'            => 'Fälliger Betrag',
    'paid'                  => 'Bezahlt',
    'histories'             => 'Historie',
    'payments'              => 'Zahlungen',
    'add_payment'           => 'Zahlung hinzufügen',
    'mark_paid'             => 'Als bezahlt markieren',
    'mark_received'         => 'Als erhalten markieren',
    'mark_cancelled'        => 'Stornieren',
    'download_pdf'          => 'Als PDF herunterladen',
    'send_mail'             => 'E-Mail senden',
    'create_bill'           => 'Rechnung erstellen',
    'receive_bill'          => 'Rechnung erhalten',
    'make_payment'          => 'Zahlung vornehmen',

    'statuses' => [
        'draft'             => 'Entwurf',
        'received'          => 'Erhalten',
        'partial'           => 'Teilweise',
        'paid'              => 'Bezahlt',
        'overdue'           => 'Überfällig',
        'unpaid'            => 'Unbezahlt',
        'cancelled'         => 'Storniert',
    ],

    'messages' => [
        'marked_received'   => 'Rechnung als erhalten markiert!',
        'marked_paid'       => 'Rechnung als bezahlt markiert!',
        'marked_cancelled'  => 'Rechnung als storniert markiert!',
        'draft'             => 'Dies ist eine Rechnungs-<b>Vorschau</b>. Die Rechnung erscheint in den Diagrammen nachdem sie als erhalten markiert wurde.',

        'status' => [
            'created'       => 'Erstellt am :date',
            'receive' => [
                'draft'     => 'Noch nicht versandt',
                'received'  => 'Empfangen am :date',
            ],
            'paid' => [
                'await'     => 'Bezahlung erwartet',
            ],
        ],
    ],

];
