<?php

return [

    'profile'               => 'प्रोफ़ाइल',
    'invoices'              => 'चालान',
    'payments'              => 'भुगतान',
    'payment_received'      => 'भुगतान प्राप्त हुआ, धन्यवाद!',
    'create_your_invoice'   => 'अब अपना खुद का चालान बनाएं — यह मुफ़्त है',
    'get_started'           => 'मुफ़्त में शुरू करें',
    'billing_address'       => 'बिल भेजने का पता',
    'see_all_details'       => 'सभी खातों के विवरण देखें',
    'all_payments'          => 'सभी भुगतान देखने के लिए लॉगिन करें',
    'received_date'         => 'प्राप्ति तिथि',
    'redirect_description'  => 'भुगतान करने के लिए आपको :name वेबसाइट पर रीडायरेक्ट कर दिया जाएगा।',

    'last_payment'          => [
        'title'             => 'अंतिम भुगतान किया गया',
        'description'       => 'आपने यह भुगतान :date को किया है',
        'not_payment'       => 'आपने अभी तक कोई भुगतान नहीं किया है।',
    ],

    'outstanding_balance'   => [
        'title'             => 'बकाया राशि',
        'description'       => 'आपकी बकाया राशि है:',
        'not_payment'       => 'आपके पास अभी तक बकाया राशि नहीं है।',
    ],

    'latest_invoices'       => [
        'title'             => 'नवीनतम चालान',
        'description'       => ':date - आपको चालान संख्या :invoice_number के साथ बिल भेजा गया था।',
        'no_data'           => 'आपको अभी तक चालान नहीं करना है।',
    ],

    'invoice_history'       => [
        'title'             => 'चालान इतिहास',
        'description'       => ':date - आपको चालान संख्या :invoice_number के साथ बिल भेजा गया था।',
        'no_data'           => 'आपके पास अभी तक चालान इतिहास नहीं है।',
    ],

    'payment_history'       => [
        'title'             => 'भुगतान इतिहास',
        'description'       => ':date - आपने :amount का भुगतान किया।',
        'invoice_description'=> ':date - आपने चालान संख्या :invoice_number के लिए :amount का भुगतान किया।',

        'no_data'           => 'आपके पास अभी तक भुगतान इतिहास नहीं है।',
    ],

    'payment_detail'        => [
        'description'       => 'आपने इस चालान के लिए :date को :amount का भुगतान किया था।'
    ],

];
