<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} lasku luotu',
        'body'          => 'Hyvä {customer_name},<br /><br />Olemme luoneet sinulle laskun numerolla <strong>{invoice_number}</strong>.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Otathan yhteyttä, mikäli sinulla herää kysyttävää.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} lasku erääntynyt',
        'body'          => 'Hyvä {customer_name},<br /><br />Tämä on ilmoitus erääntyneestä laskusta numerolla <strong>{invoice_number}</strong>.<br /><br />Laskun kokonaissumma on {invoice_total} ja se erääntyi <strong>{invoice_due_date}</strong>.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} lasku erääntynyt',
        'body'          => 'Hei,<br /><br />{customer_name} on saanut erääntymisilmoituksen laskusta numerolla <strong>{invoice_number}</strong>.<br /><br />Laskun kokonaissumma on {invoice_total} ja se erääntyi <strong>{invoice_due_date}</strong>.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} toistuva lasku luotu',
        'body'          => 'Hyvä {customer_name},<br /><br />Maksuerävalintoihisi perustuen, olemme laatineet sinulle laskun numerolla: <strong>{invoice_number}</strong>.<br /><br />Voit nähdä laskun tiedot ja jatkaa maksutapahtumaa seuraavasta linkistä: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Otathan yhteyttä, mikäli sinulla on kysyttävää.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} toistuva lasku luotu',
        'body'          => 'Hei,<br /><br />Käyttäjän {customer_name} maksuerävalintoihin perustuen, lasku numerolla <strong>{invoice_number}</strong> on luotu automaattisesti.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Maksu vastaanotettu laskulle {invoice_number}',
        'body'          => 'Hyvä {customer_name},<br /><br />Kiitos maksusta. Maksun tiedot löytyvät alta:<br /><br />-----------------------------------------------------<br />Summa: <strong>{transaction_total}</strong><br />Päivämäärä: <strong>{transaction_paid_date}</strong><br />Laskun numero: <strong>{invoice_number}</strong><br />-----------------------------------------------------------------<br /><br />Voit aina nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Otathan yhteyttä, mikäli sinulla on kysyttävää.<br /><br />Terveisin,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Maksu vastaanotettu laskulle {invoice_number}',
        'body'          => 'Hei,<br /><br />{customer_name} suoritti maksun laskulle numero <strong>{invoice_number}</strong>.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} laskun muistutusilmoitus',
        'body'          => 'Hei,<br /><br />Tämä on muistutusilmoitus laskusta <strong>{bill_number}</strong>, {vendor_name}.<br /><br />Laskun summa on yhteensä {bill_total} ja se erääntyy <strong>{bill_due_date}</strong>.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} toistuva lasku luotu',
        'body'          => 'Hei,<br /><br />Toimittajan {vendor_name} maksuerävalintoihin perustuen, lasku numerolla <strong>{bill_number}</strong> on luotu automaattisesti.<br /><br />Voit nähdä laskun tiedot seuraavasta linkistä: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Terveisin,<br />{company_name}',
    ],

];
