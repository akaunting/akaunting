<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Factura N.º {invoice_number} creada',
        'body'          => 'Estimado {customer_name},<br /><br />Hemos preparado la siguiente factura: <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en contactarnos si tiene alguna duda.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Aviso de factura vencida N.º {invoice_number}',
        'body'          => 'Estimado {customer_name},<br /><br />Este es un aviso de vencimiento de la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Aviso de factura vencida N.º {invoice_number}',
        'body'          => 'Hola,<br /><br />{customer_name} ha recibido un aviso de vencimiento por la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Factura recurrente N.º {invoice_number} creada',
        'body'          => 'Estimado {customer_name},<br /><br />De acuerdo con su ciclo recurrente, hemos preparado la siguiente factura: <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en contactarnos si tiene alguna duda.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Factura recurrente N.º {invoice_number} creada',
        'body'          => 'Hola,<br /><br />De acuerdo con el ciclo recurrente de {customer_name}, la factura <strong>{invoice_number}</strong> ha sido creada automáticamente.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Factura N.º {invoice_number} vista',
        'body'          => 'Hola,<br /><br />{customer_name} ha visto la factura <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Su recibo de la factura {invoice_number}',
        'body'          => 'Estimado {customer_name},<br /><br />Gracias por el pago. Encuentre los detalles del pago a continuación:<br /><br />-------------------------------------------------<br />Monto: <strong>{transaction_total}</strong><br />Fecha: <strong>{transaction_paid_date}</strong><br />Número de factura: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Siempre puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dude en contactarnos si tiene alguna duda.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pago recibido por la factura N.º {invoice_number}',
        'body'          => 'Hola,<br /><br />{customer_name} registró un pago por la factura <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Aviso recordatorio de factura de compra {bill_number}',
        'body'          => 'Hola,<br /><br />Este es un aviso recordatorio para la factura de compra <strong>{bill_number}</strong> de {vendor_name}.<br /><br />El total de la factura de compra a pagar es {bill_total} y vence el <strong>{bill_due_date}</strong>.<br /><br />Puede ver los detalles de la factura de compra desde el siguiente enlace: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Factura de compra recurrente {bill_number} creada',
        'body'          => 'Hola,<br /><br />De acuerdo con el ciclo recurrente de {vendor_name}, la factura de compra <strong>{bill_number}</strong> ha sido creada automáticamente.<br /><br />Puede ver los detalles de la factura de compra desde el siguiente enlace: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Su recibo de {company_name}',
        'body'          => 'Estimado {contact_name},<br /><br />Gracias por el pago. <br /><br />Puede ver los detalles del pago desde el siguiente enlace: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />No dude en contactarnos si tiene alguna duda.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Pago realizado por {company_name}',
        'body'          => 'Estimado {contact_name},<br /><br />Hemos realizado el siguiente pago. <br /><br />Puede ver los detalles del pago desde el siguiente enlace: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />No dude en contactarnos si tiene alguna duda.<br /><br />Saludos cordiales,<br />{company_name}',
    ],
];
