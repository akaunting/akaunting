<?php

return [

    'invoice_number'        => 'Factuurnummer',
    'invoice_date'          => 'Factuurdatum',
    'invoice_amount'        => 'Factuurbedrag',
    'total_price'           => 'Totaalprijs',
    'due_date'              => 'Vervaldatum',
    'order_number'          => 'Bestelnummer',
    'bill_to'               => 'Factureren aan',
    'cancel_date'           => 'Annuleringsdatum',

    'quantity'              => 'Aantal',
    'price'                 => 'Prijs',
    'sub_total'             => 'Subtotaal',
    'discount'              => 'Korting',
    'item_discount'         => 'Regelkorting',
    'tax_total'             => 'Totaal BTW',
    'total'                 => 'Totaal',

    'item_name'             => 'Artikelnaam|Artikelnamen',
    'recurring_invoices'    => 'Terugkerende Factuur|Terugkerende Facturen',

    'show_discount'         => ':discount% korting',
    'add_discount'          => 'Korting toevoegen',
    'discount_desc'         => 'van subtotaal',

    'payment_due'           => 'Te betalen voor',
    'paid'                  => 'Betaald',
    'histories'             => 'Geschiedenis',
    'payments'              => 'Betalingen',
    'add_payment'           => 'Betaling toevoegen',
    'mark_paid'             => 'Als betaald markeren',
    'mark_sent'             => 'Als verzonden markeren',
    'mark_viewed'           => 'Als bekeken markeren',
    'mark_cancelled'        => 'Als geannuleerd markeren',
    'download_pdf'          => 'PDF downloaden',
    'send_mail'             => 'E-mail versturen',
    'all_invoices'          => 'Log in om alle facturen te bekijken',
    'create_invoice'        => 'Factuur aanmaken',
    'send_invoice'          => 'Factuur versturen',
    'get_paid'              => 'Betaling ontvangen',
    'accept_payments'       => 'Online betalingen accepteren',
    'payments_received'     => 'Ontvangen betalingen',
    'over_payment'          => 'Het ingevoerde bedrag overschrijdt het totaal: :amount',

    'form_description' => [
        'billing'           => 'De factureringsgegevens verschijnen op uw factuur. De factuurdatum wordt gebruikt in het dashboard en in rapporten. Selecteer de datum waarop u verwacht betaald te worden als vervaldatum.',
    ],

    'messages' => [
        'email_required'    => 'Er is geen e-mailadres bekend van deze klant!',
        'totals_required'   => 'Factuurtotalen zijn vereist. Bewerk het :type en sla het opnieuw op.',

        'draft'             => 'Dit is een <b>CONCEPT</b> factuur en zal worden meegenomen in de statistieken zodra deze is verzonden.',

        'status' => [
            'created'       => 'Aangemaakt op :date',
            'viewed'        => 'Bekeken',
            'send' => [
                'draft'     => 'Niet verzonden',
                'sent'      => 'Verzonden op :date',
            ],
            'paid' => [
                'await'     => 'In afwachting van betaling',
            ],
        ],

        'name_or_description_required' => 'Uw factuur moet ten minste één van de volgende bevatten: <b>:name</b> of <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Uw klant kan de factuur bekijken via deze link',
        'copy_link'         => 'Kopieer de link en deel deze met uw klant.',
        'success_message'   => 'Gekopieerde deellink naar klembord!',
    ],

    'sticky' => [
        'description'       => 'U bekijkt hoe uw klant de webversie van uw factuur zal zien.',
    ],

];
