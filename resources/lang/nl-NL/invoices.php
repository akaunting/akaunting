<?php

return [

    'invoice_number'        => 'Factuurnummer',
    'invoice_date'          => 'Factuur datum',
    'invoice_amount'        => 'Factuur bedrag',
    'total_price'           => 'Totaalprijs',
    'due_date'              => 'Vervaldatum',
    'order_number'          => 'Bestelnummer',
    'bill_to'               => 'Factuur voor',
    'cancel_date'           => 'Annuleer Datum',

    'quantity'              => 'Aantal',
    'price'                 => 'Prijs',
    'sub_total'             => 'Subtotaal',
    'discount'              => 'Korting',
    'item_discount'         => 'Lijn korting',
    'tax_total'             => 'Totaal BTW',
    'total'                 => 'Totaal',

    'item_name'             => 'Artikelnaam|Artikelnamen',
    'recurring_invoices'    => 'Terugkerende Factuur|Terugkerende Facturen',

    'show_discount'         => ':discount% Korting',
    'add_discount'          => 'Korting toevoegen',
    'discount_desc'         => 'van subtotaal',

    'payment_due'           => 'Te betalen voor',
    'paid'                  => 'Betaald',
    'histories'             => 'Geschiedenis',
    'payments'              => 'Betalingen',
    'add_payment'           => 'Een betaling toevoegen',
    'mark_paid'             => 'Als betaald markeren',
    'mark_sent'             => 'Als verstuurd markeren',
    'mark_viewed'           => 'Markeren als bekeken',
    'mark_cancelled'        => 'Markeren als geannuleerd',
    'download_pdf'          => 'PDF downloaden',
    'send_mail'             => 'E-mail versturen',
    'all_invoices'          => 'Log in om alle facturen te bekijken',
    'create_invoice'        => 'Factuur maken',
    'send_invoice'          => 'Factuur sturen',
    'get_paid'              => 'Betaling afstemmen',
    'accept_payments'       => 'Online betalingen accepteren',
    'payments_received'     => 'Ontvangen betalingen',
    'over_payment'          => 'Het bedrag dat je hebt ingevoerd, komt voorbij het totaal:',

    'form_description' => [
        'billing'           => 'Factuurgegevens verschijnen op uw factuur. Factuurdatum wordt gebruikt in het dashboard en in rapporten. Selecteer de datum waarop u verwacht betaald te worden als vervaldatum.',
    ],

    'messages' => [
        'email_required'    => 'Er is geen e-mailadres bekend van deze klant!',
        'totals_required'   => 'Factuurtotalen zijn vereist Bewerk het :type en sla het opnieuw op.',

        'draft'             => 'Dit is een <b>CONCEPT</b> factuur en zal terugkomen in de statistieken wanneer het verzonden is.',

        'status' => [
            'created'       => 'Gemaakt op :date',
            'viewed'        => 'Bekeken',
            'send' => [
                'draft'     => 'Niet verstuurd',
                'sent'      => 'Verzonden op :date',
            ],
            'paid' => [
                'await'     => 'In afwachting van betaling',
            ],
        ],

        'name_or_description_required' => 'Je factuur moet ten minste één van de volgende punten bevatten <b>:name</b> of <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Uw klant kan de factuur bekijken via deze link',
        'copy_link'         => 'Kopieer de link en deel deze met uw klant.',
        'success_message'   => 'Gekopieerde deellink naar klembord!',
    ],

    'sticky' => [
        'description'       => 'Je bekijkt hoe je klant de webversie van je factuur zal zien.',
    ],

];
