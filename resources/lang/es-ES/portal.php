<?php

return [

    'profile'               => 'Perfil',
    'invoices'              => 'Facturas',
    'payments'              => 'Pagos',
    'payment_received'      => 'Pago recibido, ¡Gracias!',
    'create_your_invoice'   => 'Ahora crea tu propia factura — es gratis',
    'get_started'           => 'Comienza gratis',
    'billing_address'       => 'Dirección de facturación',
    'see_all_details'       => 'Ve los detalles de la cuenta',
    'all_payments'          => 'Inicia sesión para ver todos los pagos',
    'received_date'         => 'Fecha de recepción',

    'last_payment'          => [
        'title'             => 'Último pago realizado',
        'description'       => 'Has realizado este pago en :date',
        'not_payment'       => 'Aún no ha realizado ningún pago.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Saldo pendiente',
        'description'       => 'Su saldo pendiente es:',
        'not_payment'       => 'Todavía no tiene un saldo pendiente.',
    ],

    'latest_invoices'       => [
        'title'             => 'Últimas facturas',
        'description'       => ':date - Se ha generado la factura con el número de factura :invoice_number.',
        'no_data'           => 'Aún no tienes factura.',
    ],

    'invoice_history'       => [
        'title'             => 'Historial de Facturas',
        'description'       => ':date - Ha sido generada la factura con el número de factura :invoice_number.',
        'no_data'           => 'Aún no tienes historial de facturas.',
    ],

    'payment_history'       => [
        'title'             => 'Historial de pagos',
        'description'       => ':date - Has realizado un pago de :amount.',
        'invoice_description'=> ':date - Has realizado un pago de :amount para el número de factura :invoice_number.',

        'no_data'           => 'Aún no tienes historial de pagos.',
    ],

    'payment_detail'        => [
        'description'       => 'Hiciste un pago :amount en :date para esta factura.'
    ],

];
