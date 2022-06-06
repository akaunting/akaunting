<?php

return [

    'bill_number'           => 'Número de factura',
    'bill_date'             => 'Data de factura',
    'bill_amount'           => 'Quantitat de la factura',
    'total_price'           => 'Total',
    'due_date'              => 'Data de venciment',
    'order_number'          => 'Número de comanda',
    'bill_from'             => 'Factura de compra de',

    'quantity'              => 'Quantitat',
    'price'                 => 'Preu',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descompte',
    'item_discount'         => 'Descompte sobre el preu de línia',
    'tax_total'             => 'Total impostos',
    'total'                 => 'Total',

    'item_name'             => 'Nom de l\'article|Nom dels articles',
    'recurring_bills'       => 'Factura recurrent|Factures recurrents',

    'show_discount'         => ':discount% de descompte',
    'add_discount'          => 'Afegeix descompte',
    'discount_desc'         => 'del subtotal',

    'payment_made'          => 'S\'ha fet el pagament',
    'payment_due'           => 'Data límit de pagament',
    'amount_due'            => 'Quantitat a pagar',
    'paid'                  => 'Pagada',
    'histories'             => 'Històries',
    'payments'              => 'Pagaments',
    'add_payment'           => 'Afegeix pagament',
    'mark_paid'             => 'Marca com a pagada',
    'mark_received'         => 'Marca com a rebuda',
    'mark_cancelled'        => 'Marca com a cancel·lada',
    'download_pdf'          => 'Descarrega el PDF',
    'send_mail'             => 'Envia correu electrònic',
    'create_bill'           => 'Creació de factura',
    'receive_bill'          => 'Recepció de factura',
    'make_payment'          => 'Pagament',

    'form_description' => [
        'billing'           => 'Les dades de facturació es mostren a la factura. La data de la factura s\'utilitza al tauler i als informes. Seleccioneu la data que espereu com a data de venciment.',
    ],

    'messages' => [
        'draft'             => 'Això és un <b>ESBORRANT</b> de factura. Els canvis es veuran als gràfics un cop sigui marcada com a rebuda.',

        'status' => [
            'created'       => 'Creada el :date',
            'receive' => [
                'draft'     => 'No s\'ha rebut',
                'received'  => 'Rebut el :date',
            ],
            'paid' => [
                'await'     => 'Pagament pendent',
            ],
        ],
    ],

];
