<?php

return [

    'invoice_number'        => 'Número de factura',
    'invoice_date'          => 'Data de factura',
    'invoice_amount'        => 'Quantitat de la factura',
    'total_price'           => 'Total',
    'due_date'              => 'Data de venciment',
    'order_number'          => 'Número de comanda',
    'bill_to'               => 'Facturar a',

    'quantity'              => 'Quantitat',
    'price'                 => 'Preu',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descompte',
    'item_discount'         => 'Descompte sobre el preu de línia',
    'tax_total'             => 'Total impostos',
    'total'                 => 'Total',

    'item_name'             => 'Nom de l\'article|Nom dels articles',

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

    'messages' => [
        'email_required'    => 'Aquest client no té adreça de correu electrònic!',
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
    ],

];
