<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} facture créée',
        'body'          => 'Cher {customer_name},<br /><br />Nous avons préparé la facture suivante pour vous : <strong>{invoice_number}</strong>.<br /><br />Vous pouvez consulter les détails de la facture et procéder au paiement à partir du lien suivant : <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />N\'hésitez pas à nous contacter pour toute question.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} avis de retard de facture',
        'body'          => 'Cher/chère {customer_name},<br /><br />Ceci est une notification en retard pour la facture <strong>{invoice_number}</strong> .<br /><br />Le total de la facture est de {invoice_total} et était dû le <strong>{invoice_due_date}</strong>.<br /><br />Vous pouvez voir les détails de la facture et procéder au paiement à partir du lien suivant : <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} avis de retard de facture',
        'body'          => 'Cher/chère {customer_name},<br /><br />Ceci est une notification en retard pour la facture <strong>{invoice_number}</strong> .<br /><br />Le total de la facture est de {invoice_total} et était dû le <strong>{invoice_due_date}</strong>.<br /><br />Vous pouvez voir les détails de la facture et procéder au paiement à partir du lien suivant : <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} facture récurrente créée',
        'body'          => 'Cher {customer_name},<br /><br />Selon votre plan de facturation, nous avons préparé la facture suivante pour vous : <strong>{invoice_number}</strong>.<br /><br />Vous pouvez consulter les détails de la facture et procéder au paiement à partir du lien suivant : <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />N\'hésitez pas à nous contacter pour toute question.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{bill_number} facture récurrente créée',
        'body'          => 'Bonjour,<br /><br />Selon la facturation planifiée de  {customer_name} , <strong>{invoice_number}</strong> la facture a été automatiquement créée.<br /><br />Vous pouvez voir les détails de la facture à partir du lien suivant : <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Paiement reçu pour la facture {invoice_number}',
        'body'          => 'Cher {customer_name},<br /><br />Merci pour votre paiement. Vous Trouverez les détails de paiement ci-dessous :<br /><br />-------------------------------------------------<br />Montant : <strong>{transaction_total}</strong><br />Date : <strong>{transaction_paid_date}</strong><br />Numéro de facture : <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Vous pouvez toujours consulter les détails de la facture à partir du lien suivant : <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />N\'hésitez pas à nous contacter pour toute question.<br /><br />Cordialement,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Paiement reçu pour la facture {invoice_number}',
        'body'          => 'Bonjour,<br /><br />{customer_name} a enregistré un paiement pour la facture <strong>{invoice_number}</strong> .<br /><br />Vous pouvez voir les détails de la facture à partir du lien suivant : <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} avis de rappel de facture',
        'body'          => 'Bonjour,<br /><br />Ceci est un rappel pour la facture <strong>{bill_number}</strong> à {vendor_name}.<br /><br />Le total de la facture est de {bill_total} et est dû le <strong>{bill_due_date}</strong>.<br /><br />Vous pouvez voir les détails de la facture à partir du lien suivant : <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} facture récurrente créée',
        'body'          => 'Bonjour,<br /><br />Sur la base du plan de facturation planifié de {vendor_name} , la facture  <strong>{bill_number}</strong> a été automatiquement créée.<br /><br />Vous pouvez voir les détails de la facture à partir du lien suivant : <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordialement,<br />{company_name}',
    ],

];
