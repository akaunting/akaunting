<?php

return [

    'invoice_number'        => 'Fakturanummer',
    'invoice_date'          => 'Fakturadatum',
    'invoice_amount'        => 'Faktura belopp',
    'total_price'           => 'Summa pris',
    'due_date'              => 'Förfallodatum',
    'order_number'          => 'Ordernummer',
    'bill_to'               => 'Fakturera till',
    'cancel_date'           => 'Annulleringsdatum',

    'quantity'              => 'Antal',
    'price'                 => 'Pris',
    'sub_total'             => 'Delsumma',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Radrabatt',
    'tax_total'             => 'Summa Skatt',
    'total'                 => 'Totalt',

    'item_name'             => 'Artikelnamn | Artikelnamn',
    'recurring_invoices'    => 'Återkommande faktura|Återkommande fakturor',

    'show_discount'         => ':discount % Rabatt',
    'add_discount'          => 'Lägg till rabatt',
    'discount_desc'         => 'av delsumman',

    'payment_due'           => 'Betalning',
    'paid'                  => 'Betald',
    'histories'             => 'Historia',
    'payments'              => 'Betalningar',
    'add_payment'           => 'Lägg till betalning',
    'mark_paid'             => 'Markera som betald',
    'mark_sent'             => 'Markera som skickad',
    'mark_viewed'           => 'Markera som visad',
    'mark_cancelled'        => 'Markera som avbruten',
    'download_pdf'          => 'Ladda ner PDF',
    'send_mail'             => 'Skicka E-post',
    'all_invoices'          => 'Logga in för att visa alla fakturor',
    'create_invoice'        => 'Skapa faktura',
    'send_invoice'          => 'Skicka faktura',
    'get_paid'              => 'Få betalt',
    'accept_payments'       => 'Acceptera onlinebetalningar',
    'payment_received'      => 'Betalning mottagen',

    'form_description' => [
        'billing'           => 'Faktureringsuppgifter visas i din faktura. Faktureringsdatum används i instrumentpanelen och rapporter. Välj det datum du förväntar dig att få betalt som förfallodatum.',
    ],

    'messages' => [
        'email_required'    => 'Ingen e-postadress för den här kunden!',
        'draft'             => 'Detta är en <b>utkast</b> faktura och kommer att speglas till diagramet efter det skickas.',

        'status' => [
            'created'       => 'Skapad den :date',
            'viewed'        => 'Visad',
            'send' => [
                'draft'     => 'Inte skickat',
                'sent'      => 'Skickat den :date',
            ],
            'paid' => [
                'await'     => 'Väntar på betalning',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user skapade denna faktura :date',
        'create_recurring'  => ':user skapade den här återkommande mallen :date',
        'schedule'          => 'Upprepa varje :interval :frequency sedan :date',
        'children'          => ':count fakturor skapades automatiskt',
    ],

    'share' => [
        'show_link'         => 'Din kund kan se fakturan på denna länk',
        'copy_link'         => 'Kopiera länken och dela den med din kund.',
        'success_message'   => 'Kopierade delningslänk till urklipp!',
    ],

    'sticky' => [
        'description'       => 'Du förhandsgranskar hur din kund ser webbversionen av din faktura.',
    ],

];
