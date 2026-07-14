<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Factura {invoice_number} creada',
        'body'          => 'Hola, {customer_name}:<br /><br />Hemos preparado la siguiente factura para usted: <strong>{invoice_number}</strong>.<br /><br />Puede consultar los datos de la factura y realizar el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en ponerse en contacto con nosotros si tiene alguna pregunta.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Aviso de vencimiento de la factura {invoice_number}',
        'body'          => 'Hola, {customer_name}:<br /><br />Le informamos de que la factura <strong>{invoice_number}</strong> está vencida.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede consultar los datos de la factura y realizar el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Aviso de vencimiento de la factura {invoice_number}',
        'body'          => 'Hola:<br /><br />{customer_name} ha recibido un aviso de vencimiento de la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Factura recurrente {invoice_number} creada',
        'body'          => 'Hola, {customer_name}:<br /><br />De acuerdo con la periodicidad configurada, hemos preparado la siguiente factura para usted: <strong>{invoice_number}</strong>.<br /><br />Puede consultar los datos de la factura y realizar el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en ponerse en contacto con nosotros si tiene alguna pregunta.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Factura recurrente {invoice_number} creada',
        'body'          => 'Hola:<br /><br />De acuerdo con la periodicidad configurada para {customer_name}, la factura <strong>{invoice_number}</strong> se ha creado automáticamente.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Factura {invoice_number} consultada',
        'body'          => 'Hola:<br /><br />{customer_name} ha consultado la factura <strong>{invoice_number}</strong>.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Recibo del pago de la factura {invoice_number}',
        'body'          => 'Hola, {customer_name}:<br /><br />Gracias por su pago. A continuación se muestran los datos del pago:<br /><br />-------------------------------------------------<br />Importe: <strong>{transaction_total}</strong><br />Fecha: <strong>{transaction_paid_date}</strong><br />Número de factura: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Puede consultar en cualquier momento los datos de la factura desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en ponerse en contacto con nosotros si tiene alguna pregunta.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pago recibido de la factura {invoice_number}',
        'body'          => 'Hola:<br /><br />{customer_name} ha registrado un pago de la factura <strong>{invoice_number}</strong>.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Recordatorio de la factura de compra {bill_number}',
        'body'          => 'Hola:<br /><br />Este es un recordatorio de la factura de compra <strong>{bill_number}</strong> del proveedor {vendor_name}.<br /><br />El total de la factura es {bill_total} y vence el <strong>{bill_due_date}</strong>.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Factura de compra recurrente {bill_number} creada',
        'body'          => 'Hola:<br /><br />De acuerdo con la periodicidad configurada para {vendor_name}, la factura de compra <strong>{bill_number}</strong> se ha creado automáticamente.<br /><br />Puede consultar los datos de la factura desde el siguiente enlace: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Su recibo de {company_name}',
        'body'          => 'Hola, {contact_name}:<br /><br />Gracias por su pago. <br /><br />Puede consultar los datos del pago desde el siguiente enlace: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />No dude en ponerse en contacto con nosotros si tiene alguna pregunta.<br /><br />Un cordial saludo,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Pago realizado por {company_name}',
        'body'          => 'Hola, {contact_name}:<br /><br />Hemos realizado el siguiente pago. <br /><br />Puede consultar los datos del pago desde el siguiente enlace: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />No dude en ponerse en contacto con nosotros si tiene alguna pregunta.<br /><br />Un cordial saludo,<br />{company_name}',
    ],
];
