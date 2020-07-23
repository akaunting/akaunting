<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} factura creada',
        'body'          => 'Estimado {customer_name},<br /><br />Le hemos preparado la siguiente factura: <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Ponte en contacto con nosotros para cualquier pregunta.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} notificación de factura vencida',
        'body'          => 'Estimado {customer_name},<br /><br />Este es un aviso de factura vencida para <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} notificación de factura vencida',
        'body'          => 'Hola,<br /><br />{customer_name} ha recibido un aviso de vencimiento por la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} factura recurrente creada',
        'body'          => 'Estimado {customer_name},<br /><br />Basado en su círculo recurrente, le hemos preparado la siguiente factura: <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura y proceder con el pago desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Ponte en contacto con nosotros para cualquier pregunta.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} factura recurrente creada',
        'body'          => 'Hola,<br /><br />Basado en el círculo recurrente de {customer_name}, la factura <strong>{invoice_number}</strong> ha sido creada automáticamente.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pago recibido por factura {invoice_number}',
        'body'          => 'Estimado {customer_name},<br /><br />Gracias por el pago. Encuentre los detalles de pago a continuación:<br /><br />-------------------------------------------------<br /><br />Monto: <strong>{transaction_total}<br /></strong>Fecha: <strong>{transaction_paid_date}</strong><br />Número de factura: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Siempre puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Ponte en contacto con nosotros para cualquier pregunta.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pago recibido por factura {invoice_number}',
        'body'          => 'Hola,<br /><br />{customer_name} registró un pago por la factura <strong>{invoice_number}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Aviso recordatorio de factura {bill_number}',
        'body'          => 'Hola,<br /><br />{customer_name} ha recibido un aviso de vencimiento por la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura es {invoice_total} y venció el <strong>{invoice_due_date}</strong>.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} factura recurrente creada',
        'body'          => 'Hola,<br /><br />Basado en el círculo recurrente de {customer_name}, la factura <strong>{invoice_number}</strong> ha sido creada automáticamente.<br /><br />Puede ver los detalles de la factura desde el siguiente enlace: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Saludos cordiales,<br />{company_name}',
    ],

];
