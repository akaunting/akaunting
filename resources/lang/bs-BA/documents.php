<?php

return [

    'edit_columns'              => 'Izmjena kolona',
    'empty_items'               => 'Niste dodali nijednu stavku.',
    'grand_total'               => 'TOTAL',
    'accept_payment_online'     => 'Prihvatite plaćanja na mreži',
    'transaction'               => 'Plaćanje za :amount je izvršeno pomoću :account.',
    'billing'                   => 'Naplata',
    'advanced'                  => 'Napredno',

    'invoice_detail' => [
        'marked'                => '<b> Vi </b> označili ste ovu fakturu kao',
        'services'              => 'Usluge',
        'another_item'          => 'Druga stavka',
        'another_description'   => 'i drugi opis',
        'more_item'             => '+:count više stavki',
    ],

    'statuses' => [
        'draft'                 => 'U pripremi',
        'sent'                  => 'Poslano',
        'expired'               => 'Isteklo',
        'viewed'                => 'Pregledano',
        'approved'              => 'Odobreno',
        'received'              => 'Primjeno',
        'refused'               => 'Odbijeno',
        'restored'              => 'Vraćeno',
        'reversed'              => 'Obrnuto',
        'partial'               => 'Djelomično',
        'paid'                  => 'Plaćeno',
        'pending'               => 'Na čekanju',
        'invoiced'              => 'Fakturisano',
        'overdue'               => 'Kasni uplata',
        'unpaid'                => 'Neplaćeno',
        'cancelled'             => 'Otkazano',
        'voided'                => 'Poništeno',
        'completed'             => 'Završenoi',
        'shipped'               => 'Dostavljeno',
        'refunded'              => 'Izvršiti povrat novca',
        'failed'                => 'Neuspješno',
        'denied'                => 'Odbijenos',
        'processed'             => 'U obradi',
        'open'                  => 'Otvoreno',
        'closed'                => 'Zatvoreno',
        'billed'                => 'Naplaćeno',
        'delivered'             => 'Dostavljeno',
        'returned'              => 'Vraćeno',
        'drawn'                 => 'Izvučeno',
        'not_billed'            => 'Nije naplaćeno',
        'issued'                => 'Izdato',
        'not_invoiced'          => 'Nije fakturisano',
        'confirmed'             => 'Potvrđeno',
        'not_confirmed'         => 'Nije potvrđeno',
        'active'                => 'Aktivan',
        'ended'                 => 'Završeno',
    ],

    'form_description' => [
        'companies'             => 'Promijenite adresu, logo i druge informacije za svoju kompaniju.',
        'billing'               => 'Detalji naplate se pojavljuju u vašem dokumentu.',
        'advanced'              => 'Odaberite kategoriju, dodajte ili uredite podnožje i dodajte priloge svom :type.',
        'attachment'            => 'Preuzmite datoteke priložene ovom :type',
    ],

    'messages' => [
        'email_sent'            => ':type email je poslan!',
        'marked_as'             => ':type prebačen je u :status!',
        'marked_sent'           => ':type označen je kao poslan!',
        'marked_paid'           => ':type označen je kao plaćen!',
        'marked_viewed'         => ':type označen je kao pregledan!',
        'marked_cancelled'      => ':type označen je kao otkazan!',
        'marked_received'       => ':type označen je kao primljen!',
    ],

    'recurring' => [
        'auto_generated'        => 'Auto-generisani',

        'tooltip' => [
            'document_date'     => 'Datum :type će biti automatski dodijeljen na osnovu :type rasporeda i učestalosti.',
            'document_number'   => 'Broj :type će se automatski dodijeliti kada se generira svaki ponavljajući :type.',
        ],
    ],

];
