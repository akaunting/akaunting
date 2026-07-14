<?php

return [

    'profile'               => 'Perfil',
    'invoices'              => 'Facturas',
    'payments'              => 'Pagos',
    'payment_received'      => 'Pago recibido. Gracias.',
    'create_your_invoice'   => 'Cree ahora su propia factura: es gratis',
    'get_started'           => 'Comience gratis',
    'billing_address'       => 'Dirección de facturación',
    'see_all_details'       => 'Ver todos los datos de la cuenta',
    'all_payments'          => 'Inicie sesión para ver todos los pagos',
    'received_date'         => 'Fecha de recepción',
    'redirect_description'  => 'Será redirigido al sitio web de :name para realizar el pago.',

    'last_payment'          => [
        'title'             => 'Último pago realizado',
        'description'       => 'Realizó este pago el :date',
        'not_payment'       => 'Aún no ha realizado ningún pago.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Saldo pendiente',
        'description'       => 'Su saldo pendiente es:',
        'not_payment'       => 'Todavía no tiene un saldo pendiente.',
    ],

    'latest_invoices'       => [
        'title'             => 'Últimas facturas',
        'description'       => ':date - Se le facturó mediante la factura número :invoice_number.',
        'no_data'           => 'Todavía no tiene ninguna factura.',
    ],

    'invoice_history'       => [
        'title'             => 'Historial de facturas',
        'description'       => ':date - Se le facturó mediante la factura número :invoice_number.',
        'no_data'           => 'Todavía no tiene historial de facturas.',
    ],

    'payment_history'       => [
        'title'             => 'Historial de pagos',
        'description'       => ':date - Realizó un pago de :amount.',
        'invoice_description'=> ':date - Realizó un pago de :amount para la factura número :invoice_number.',

        'no_data'           => 'Todavía no tiene historial de pagos.',
    ],

    'payment_detail'        => [
        'description'       => 'Realizó un pago de :amount el :date para esta factura.'
    ],

];
