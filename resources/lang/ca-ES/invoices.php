<?php

return [

    'invoice_number'        => 'Número de factura',
    'invoice_date'          => 'Data de factura',
    'invoice_amount'        => 'Quantitat de la factura',
    'total_price'           => 'Total',
    'due_date'              => 'Data de venciment',
    'order_number'          => 'Número de comanda',
    'bill_to'               => 'Facturar a',
    'cancel_date'           => 'Data de cancel·lació',

    'quantity'              => 'Quantitat',
    'price'                 => 'Preu',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descompte',
    'item_discount'         => 'Descompte sobre el preu de línia',
    'tax_total'             => 'Total impostos',
    'total'                 => 'Total',

    'item_name'             => 'Nom de l\'article|Nom dels articles',
    'recurring_invoices'    => 'Factura recurrent|Factures recurrents',

    'show_discount'         => ':discount% de descompte',
    'add_discount'          => 'Afegeix descompte',
    'discount_desc'         => 'del subtotal',

    'payment_due'           => 'Data límit de pagament',
    'paid'                  => 'Pagada',
    'histories'             => 'Històries',
    'payments'              => 'Cobraments',
    'add_payment'           => 'Afegeix cobrament',
    'mark_paid'             => 'Marca com a cobrada',
    'mark_sent'             => 'Marca com enviada',
    'mark_viewed'           => 'Marca com a vista',
    'mark_cancelled'        => 'Marca com a cancel·lada',
    'download_pdf'          => 'Descarrega el PDF',
    'send_mail'             => 'Envia correu electrònic',
    'all_invoices'          => 'Registra\'t per veure les factures',
    'create_invoice'        => 'Creació de factura',
    'send_invoice'          => 'Enviament de factura',
    'get_paid'              => 'Cobrament',
    'accept_payments'       => 'Accepta pagaments online',
    'payments_received'     => 'S\'han rebut els pagaments',

    'form_description' => [
        'billing'           => 'Les dades de facturació es mostren a la factura. La data de la factura s\'utilitza al tauler i als informes. Selecciona la data que vols com a data de venciment.',
    ],

    'messages' => [
        'email_required'    => 'Aquest client no té adreça de correu electrònic!',
        'totals_required'   => 'Cal incloure el total de les factures. Si us plau edita :type i grava\'l novament.',

        'draft'             => 'Això és un <b>ESBORRANY</b> de factura i es reflectirà als gràfics un cop s\'hagi enviat.',

        'status' => [
            'created'       => 'Creada el :date',
            'viewed'        => 'Vista',
            'send' => [
                'draft'     => 'No s\'ha enviat',
                'sent'      => 'Enviada el :date',
            ],
            'paid' => [
                'await'     => 'Cobrament pendent',
            ],
        ],

        'name_or_description_required' => 'La teva factura ha de mostrar com a mínim o <b>:name</b> o <b>:description</b>.',
    ],

    'slider' => [
        'create'            => ':user ha creat aquesta factura el :date',
        'create_recurring'  => ':user ha creat aquesta plantilla recurrent el :date',
        'schedule'          => 'Repeteix cada :interval :frequency des de :date',
        'children'          => 'S\'han creat :count factures automàticament',
    ],

    'share' => [
        'show_link'         => 'El teu client pot veure la factura des d\'aquest enllaç',
        'copy_link'         => 'Copia l\'enllaç i comparteix-lo amb el teu client.',
        'success_message'   => 'S\'ha copiat l\'enllaç per compartir al porta-retalls!',
    ],

    'sticky' => [
        'description'       => 'Previsualització de la versió web de la teva factura que veurà el teu client.',
    ],

];
